<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffReportController extends Controller
{
    private function canReviewAll(): bool
    {
        $role = Auth::user()->role;
        return in_array($role, ['admin', 'head_of_operations']);
    }

    public function index(Request $request)
    {
        $query = Report::with('submittedBy', 'reviewedBy')->latest();

        if (!$this->canReviewAll()) {
            $query->ownedBy(Auth::id());
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $reports = $query->paginate(20)->withQueryString();

        $pendingCount = $this->canReviewAll()
            ? Report::pendingReview()->count()
            : Report::pendingReview()->ownedBy(Auth::id())->count();

        return view('staff-reports.index', [
            'reports'         => $reports,
            'pendingCount'    => $pendingCount,
            'reportTypes'     => Report::$reportTypes,
            'classifications' => Report::$classifications,
            'canReviewAll'    => $this->canReviewAll(),
        ]);
    }

    public function create()
    {
        return view('staff-reports.create', [
            'reportTypes'     => Report::$reportTypes,
            'classifications' => Report::$classifications,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'report_type'    => 'required|in:' . implode(',', array_keys(Report::$reportTypes)),
            'classification' => 'required|in:' . implode(',', array_keys(Report::$classifications)),
            'content'        => 'nullable|string',
            'file'           => 'nullable|file|mimes:pdf,doc,docx,txt,xlsx,xls|max:10240',
            'period_start'   => 'nullable|date',
            'period_end'     => 'nullable|date|after_or_equal:period_start',
        ]);

        if (!$validated['content'] && !$request->hasFile('file')) {
            return back()->withInput()->with('error', 'Please provide report content or upload a file.');
        }

        $filePath = null;
        $fileOriginalName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileOriginalName = $file->getClientOriginalName();
            $filePath = $file->store('staff-reports', 'private');
        }

        $action = $request->input('action', 'draft');

        $report = Report::create([
            'title'              => $validated['title'],
            'report_type'        => $validated['report_type'],
            'classification'     => $validated['classification'],
            'content'            => $validated['content'],
            'file_path'          => $filePath,
            'file_original_name' => $fileOriginalName,
            'status'             => $action === 'submit' ? 'submitted' : 'draft',
            'period_start'       => $validated['period_start'],
            'period_end'         => $validated['period_end'],
            'submitted_by'       => Auth::id(),
            'submitted_at'       => $action === 'submit' ? now() : null,
        ]);

        $message = $action === 'submit'
            ? 'Report submitted for approval.'
            : 'Report saved as draft.';

        return redirect()->route('staff-reports.show', $report)->with('success', $message);
    }

    public function show(Report $staffReport)
    {
        if (!$this->canReviewAll() && $staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        $staffReport->load('submittedBy', 'reviewedBy');

        return view('staff-reports.show', [
            'report'          => $staffReport,
            'reportTypes'     => Report::$reportTypes,
            'classifications' => Report::$classifications,
            'canReviewAll'    => $this->canReviewAll(),
        ]);
    }

    public function edit(Report $staffReport)
    {
        if ($staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        if (!$staffReport->isEditable()) {
            return redirect()->route('staff-reports.show', $staffReport)
                ->with('error', 'Approved reports cannot be edited.');
        }

        return view('staff-reports.edit', [
            'report'          => $staffReport,
            'reportTypes'     => Report::$reportTypes,
            'classifications' => Report::$classifications,
        ]);
    }

    public function update(Request $request, Report $staffReport)
    {
        if ($staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        if (!$staffReport->isEditable()) {
            return redirect()->route('staff-reports.show', $staffReport)
                ->with('error', 'Approved reports cannot be edited.');
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'report_type'    => 'required|in:' . implode(',', array_keys(Report::$reportTypes)),
            'classification' => 'required|in:' . implode(',', array_keys(Report::$classifications)),
            'content'        => 'nullable|string',
            'file'           => 'nullable|file|mimes:pdf,doc,docx,txt,xlsx,xls|max:10240',
            'period_start'   => 'nullable|date',
            'period_end'     => 'nullable|date|after_or_equal:period_start',
        ]);

        if (!$validated['content'] && !$request->hasFile('file') && !$staffReport->file_path) {
            return back()->withInput()->with('error', 'Please provide report content or upload a file.');
        }

        if ($request->hasFile('file')) {
            if ($staffReport->file_path) {
                Storage::disk('private')->delete($staffReport->file_path);
            }
            $file = $request->file('file');
            $validated['file_original_name'] = $file->getClientOriginalName();
            $validated['file_path'] = $file->store('staff-reports', 'private');
        }

        $action = $request->input('action', 'draft');

        $staffReport->update([
            'title'          => $validated['title'],
            'report_type'    => $validated['report_type'],
            'classification' => $validated['classification'],
            'content'        => $validated['content'],
            'file_path'      => $validated['file_path'] ?? $staffReport->file_path,
            'file_original_name' => $validated['file_original_name'] ?? $staffReport->file_original_name,
            'status'         => $action === 'submit' ? 'submitted' : 'draft',
            'period_start'   => $validated['period_start'],
            'period_end'     => $validated['period_end'],
            'submitted_at'   => $action === 'submit' ? now() : null,
            'reviewed_by'    => null,
            'reviewed_at'    => null,
            'review_notes'   => null,
        ]);

        $message = $action === 'submit'
            ? 'Report submitted for approval.'
            : 'Report saved as draft.';

        return redirect()->route('staff-reports.show', $staffReport)->with('success', $message);
    }

    public function submit(Report $staffReport)
    {
        if ($staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        if (!$staffReport->isDraft() && !$staffReport->isRejected()) {
            return back()->with('error', 'Only draft or rejected reports can be submitted.');
        }

        $staffReport->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
            'reviewed_by'  => null,
            'reviewed_at'  => null,
            'review_notes' => null,
        ]);

        return redirect()->route('staff-reports.show', $staffReport)
            ->with('success', 'Report submitted for approval.');
    }

    public function approve(Request $request, Report $staffReport)
    {
        if (!$this->canReviewAll()) {
            abort(403);
        }

        if (!$staffReport->isSubmitted()) {
            return back()->with('error', 'Only submitted reports can be approved.');
        }

        $request->validate(['review_notes' => 'nullable|string|max:1000']);

        $staffReport->update([
            'status'       => 'approved',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        Notification::create([
            'user_id' => $staffReport->submitted_by,
            'sent_by' => Auth::id(),
            'type'    => 'report_approved',
            'title'   => 'Report Approved',
            'message' => "Your report \"{$staffReport->title}\" has been approved." .
                         ($request->review_notes ? " Note: {$request->review_notes}" : ''),
            'data'    => ['report_id' => $staffReport->id],
            'is_read' => false,
        ]);

        return redirect()->route('staff-reports.show', $staffReport)
            ->with('success', 'Report approved and staff notified.');
    }

    public function reject(Request $request, Report $staffReport)
    {
        if (!$this->canReviewAll()) {
            abort(403);
        }

        if (!$staffReport->isSubmitted()) {
            return back()->with('error', 'Only submitted reports can be rejected.');
        }

        $request->validate(['review_notes' => 'required|string|max:1000']);

        $staffReport->update([
            'status'       => 'rejected',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        Notification::create([
            'user_id' => $staffReport->submitted_by,
            'sent_by' => Auth::id(),
            'type'    => 'report_rejected',
            'title'   => 'Report Needs Revision',
            'message' => "Your report \"{$staffReport->title}\" requires revision. Reason: {$request->review_notes}",
            'data'    => ['report_id' => $staffReport->id],
            'is_read' => false,
        ]);

        return redirect()->route('staff-reports.show', $staffReport)
            ->with('success', 'Report rejected and staff notified.');
    }

    public function destroy(Report $staffReport)
    {
        if ($staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        if (!$staffReport->isDraft()) {
            return back()->with('error', 'Only draft reports can be deleted.');
        }

        if ($staffReport->file_path) {
            Storage::disk('private')->delete($staffReport->file_path);
        }

        $staffReport->delete();

        return redirect()->route('staff-reports.index')
            ->with('success', 'Report deleted.');
    }

    public function download(Report $staffReport)
    {
        if (!$this->canReviewAll() && $staffReport->submitted_by !== Auth::id()) {
            abort(403);
        }

        if (!$staffReport->file_path || !Storage::disk('private')->exists($staffReport->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download(
            $staffReport->file_path,
            $staffReport->file_original_name
        );
    }
}
