<?php

namespace App\Http\Controllers;

use App\Mail\RequisitionDecisionMail;
use App\Mail\RequisitionSubmittedMail;
use App\Models\Notification;
use App\Models\Requisition;
use App\Models\RequisitionDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RequisitionController extends Controller
{
    private function canReviewAll(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'head_of_operations']);
    }

    private function ip(): string
    {
        return request()->ip() ?? 'unknown';
    }

    // ─── Listing ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Requisition::with('submittedBy', 'reviewedBy', 'documents')->latest();

        if (!$this->canReviewAll()) {
            $query->ownedBy(Auth::id());
        }

        if ($request->filled('status'))           $query->byStatus($request->status);
        if ($request->filled('requisition_type')) $query->where('requisition_type', $request->requisition_type);
        if ($request->filled('priority'))         $query->where('priority', $request->priority);
        if ($request->filled('search'))           $query->where('title', 'like', '%' . $request->search . '%');

        $requisitions = $query->paginate(20)->withQueryString();

        $pendingCount = $this->canReviewAll()
            ? Requisition::pendingReview()->count()
            : 0;

        return view('requisitions.index', [
            'requisitions' => $requisitions,
            'pendingCount' => $pendingCount,
            'types'        => Requisition::$types,
            'priorities'   => Requisition::$priorities,
            'canReviewAll' => $this->canReviewAll(),
        ]);
    }

    // ─── Create / Store ─────────────────────────────────────────────────────────

    public function create()
    {
        return view('requisitions.create', [
            'types'      => Requisition::$types,
            'priorities' => Requisition::$priorities,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'requisition_type' => 'required|in:' . implode(',', array_keys(Requisition::$types)),
            'description'      => 'required|string',
            'justification'    => 'nullable|string',
            'amount'           => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|max:10',
            'priority'         => 'required|in:' . implode(',', array_keys(Requisition::$priorities)),
            'needed_by_date'   => 'nullable|date|after_or_equal:today',
            'documents.*'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
        ]);

        $action = $request->input('action', 'draft');

        $requisition = Requisition::create([
            'title'            => $validated['title'],
            'requisition_type' => $validated['requisition_type'],
            'description'      => $validated['description'],
            'justification'    => $validated['justification'],
            'amount'           => $validated['amount'],
            'currency'         => $validated['currency'] ?? 'USD',
            'priority'         => $validated['priority'],
            'needed_by_date'   => $validated['needed_by_date'],
            'status'           => $action === 'submit' ? 'submitted' : 'draft',
            'submitted_by'     => Auth::id(),
            'submitted_at'     => $action === 'submit' ? now() : null,
        ]);

        $this->storeDocuments($request, $requisition);

        $requisition->logAction('created', Auth::id(), null, $requisition->status,
            'Requisition created' . ($action === 'submit' ? ' and submitted for approval' : ' as draft'),
            $this->ip()
        );

        if ($action === 'submit') {
            $this->notifyReviewers($requisition);
        }

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', $action === 'submit' ? 'Requisition submitted for approval.' : 'Requisition saved as draft.');
    }

    // ─── Show ────────────────────────────────────────────────────────────────────

    public function show(Requisition $requisition)
    {
        if (!$this->canReviewAll() && $requisition->submitted_by !== Auth::id()) {
            abort(403);
        }

        $requisition->load('submittedBy', 'reviewedBy', 'documents.uploadedBy', 'auditLogs.performedBy');

        return view('requisitions.show', [
            'requisition'  => $requisition,
            'types'        => Requisition::$types,
            'priorities'   => Requisition::$priorities,
            'canReviewAll' => $this->canReviewAll(),
        ]);
    }

    // ─── Edit / Update ───────────────────────────────────────────────────────────

    public function edit(Requisition $requisition)
    {
        if ($requisition->submitted_by !== Auth::id()) abort(403);
        if (!$requisition->isEditable()) {
            return redirect()->route('requisitions.show', $requisition)
                ->with('error', 'Only draft or rejected requisitions can be edited.');
        }

        return view('requisitions.edit', [
            'requisition' => $requisition,
            'types'       => Requisition::$types,
            'priorities'  => Requisition::$priorities,
        ]);
    }

    public function update(Request $request, Requisition $requisition)
    {
        if ($requisition->submitted_by !== Auth::id()) abort(403);
        if (!$requisition->isEditable()) {
            return redirect()->route('requisitions.show', $requisition)
                ->with('error', 'Approved requisitions cannot be edited.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'requisition_type' => 'required|in:' . implode(',', array_keys(Requisition::$types)),
            'description'      => 'required|string',
            'justification'    => 'nullable|string',
            'amount'           => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|max:10',
            'priority'         => 'required|in:' . implode(',', array_keys(Requisition::$priorities)),
            'needed_by_date'   => 'nullable|date',
            'documents.*'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
        ]);

        $action    = $request->input('action', 'draft');
        $oldStatus = $requisition->status;
        $newStatus = $action === 'submit' ? 'submitted' : 'draft';

        $requisition->update([
            'title'            => $validated['title'],
            'requisition_type' => $validated['requisition_type'],
            'description'      => $validated['description'],
            'justification'    => $validated['justification'],
            'amount'           => $validated['amount'],
            'currency'         => $validated['currency'] ?? $requisition->currency,
            'priority'         => $validated['priority'],
            'needed_by_date'   => $validated['needed_by_date'],
            'status'           => $newStatus,
            'submitted_at'     => $action === 'submit' ? now() : null,
            'reviewed_by'      => null,
            'reviewed_at'      => null,
            'review_notes'     => null,
        ]);

        $this->storeDocuments($request, $requisition);

        $actionLabel = $oldStatus === 'rejected' ? 'resubmitted' : ($action === 'submit' ? 'submitted' : 'updated');
        $requisition->logAction($actionLabel, Auth::id(), $oldStatus, $newStatus,
            $actionLabel === 'resubmitted' ? 'Revised and resubmitted after rejection' : null,
            $this->ip()
        );

        if ($action === 'submit') {
            $this->notifyReviewers($requisition);
        }

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', $action === 'submit' ? 'Requisition submitted for approval.' : 'Requisition updated.');
    }

    // ─── Submit (standalone action) ──────────────────────────────────────────────

    public function submit(Requisition $requisition)
    {
        if ($requisition->submitted_by !== Auth::id()) abort(403);
        if (!$requisition->isDraft() && !$requisition->isRejected()) {
            return back()->with('error', 'Only draft or rejected requisitions can be submitted.');
        }

        $old = $requisition->status;
        $requisition->update(['status' => 'submitted', 'submitted_at' => now(),
            'reviewed_by' => null, 'reviewed_at' => null, 'review_notes' => null]);

        $label = $old === 'rejected' ? 'resubmitted' : 'submitted';
        $requisition->logAction($label, Auth::id(), $old, 'submitted', null, $this->ip());

        $this->notifyReviewers($requisition);

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Requisition submitted for approval.');
    }

    // ─── Approve ────────────────────────────────────────────────────────────────

    public function approve(Request $request, Requisition $requisition)
    {
        if (!$this->canReviewAll()) abort(403);
        if (!$requisition->isSubmitted()) {
            return back()->with('error', 'Only submitted requisitions can be approved.');
        }

        $request->validate(['review_notes' => 'nullable|string|max:2000']);

        $requisition->update([
            'status'       => 'approved',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        $requisition->logAction('approved', Auth::id(), 'submitted', 'approved',
            $request->review_notes, $this->ip());

        $this->notifyStaff($requisition, 'approved');

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Requisition approved and staff notified by email.');
    }

    // ─── Reject ─────────────────────────────────────────────────────────────────

    public function reject(Request $request, Requisition $requisition)
    {
        if (!$this->canReviewAll()) abort(403);
        if (!$requisition->isSubmitted()) {
            return back()->with('error', 'Only submitted requisitions can be rejected.');
        }

        $request->validate(['review_notes' => 'required|string|max:2000']);

        $requisition->update([
            'status'       => 'rejected',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        $requisition->logAction('rejected', Auth::id(), 'submitted', 'rejected',
            $request->review_notes, $this->ip());

        $this->notifyStaff($requisition, 'rejected');

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Requisition rejected and staff notified by email.');
    }

    // ─── Delete ─────────────────────────────────────────────────────────────────

    public function destroy(Requisition $requisition)
    {
        if ($requisition->submitted_by !== Auth::id()) abort(403);
        if (!$requisition->isDraft()) {
            return back()->with('error', 'Only draft requisitions can be deleted.');
        }

        foreach ($requisition->documents as $doc) {
            Storage::disk('private')->delete($doc->file_path);
        }

        $requisition->delete();

        return redirect()->route('requisitions.index')->with('success', 'Requisition deleted.');
    }

    // ─── Document download ───────────────────────────────────────────────────────

    public function downloadDocument(Requisition $requisition, RequisitionDocument $document)
    {
        if (!$this->canReviewAll() && $requisition->submitted_by !== Auth::id()) abort(403);
        if ($document->requisition_id !== $requisition->id) abort(404);

        if (!Storage::disk('private')->exists($document->file_path)) abort(404, 'File not found.');

        return Storage::disk('private')->download($document->file_path, $document->file_original_name);
    }

    // ─── Private helpers ─────────────────────────────────────────────────────────

    private function storeDocuments(Request $request, Requisition $requisition): void
    {
        if (!$request->hasFile('documents')) return;

        foreach ($request->file('documents') as $file) {
            if (!$file->isValid()) continue;

            $path = $file->store('requisitions/' . $requisition->id, 'private');

            $doc = $requisition->documents()->create([
                'file_path'          => $path,
                'file_original_name' => $file->getClientOriginalName(),
                'file_size'          => $file->getSize(),
                'mime_type'          => $file->getMimeType(),
                'uploaded_by'        => Auth::id(),
            ]);

            $requisition->logAction('document_uploaded', Auth::id(), null, null,
                "Uploaded: {$doc->file_original_name}", $this->ip());
        }
    }

    private function notifyReviewers(Requisition $requisition): void
    {
        $requisition->load('submittedBy');
        $submitter = $requisition->submittedBy;

        $reviewers = User::whereIn('role', ['admin', 'head_of_operations'])
            ->where('is_active', true)
            ->whereNotNull('email')
            ->get();

        foreach ($reviewers as $reviewer) {
            try {
                Mail::to($reviewer->email)->send(new RequisitionSubmittedMail($requisition, $submitter));
            } catch (\Throwable $e) {
                Log::error("Failed to send requisition submission email to {$reviewer->email}: " . $e->getMessage());
            }

            Notification::create([
                'user_id' => $reviewer->id,
                'sent_by' => $submitter->id,
                'type'    => 'requisition_submitted',
                'title'   => 'New Requisition Awaiting Approval',
                'message' => "\"{$requisition->title}\" submitted by {$submitter->name} requires your review.",
                'data'    => ['requisition_id' => $requisition->id],
                'is_read' => false,
            ]);
        }
    }

    private function notifyStaff(Requisition $requisition, string $decision): void
    {
        $requisition->load('submittedBy', 'reviewedBy');
        $staff    = $requisition->submittedBy;
        $reviewer = $requisition->reviewedBy ?? Auth::user();

        try {
            Mail::to($staff->email)->send(new RequisitionDecisionMail($requisition, $reviewer, $decision));
        } catch (\Throwable $e) {
            Log::error("Failed to send requisition decision email to {$staff->email}: " . $e->getMessage());
        }

        $label = $decision === 'approved' ? 'Requisition Approved' : 'Requisition Requires Revision';
        $msg   = $decision === 'approved'
            ? "Your requisition \"{$requisition->title}\" has been approved."
            : "Your requisition \"{$requisition->title}\" was rejected. Reason: {$requisition->review_notes}";

        Notification::create([
            'user_id' => $staff->id,
            'sent_by' => Auth::id(),
            'type'    => "requisition_{$decision}",
            'title'   => $label,
            'message' => $msg,
            'data'    => ['requisition_id' => $requisition->id],
            'is_read' => false,
        ]);
    }
}
