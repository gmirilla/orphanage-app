<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Facility;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_children'      => Child::where('is_active', true)->count(),
            'total_donors'        => Donor::count(),
            'total_donations'     => Donation::where('status', 'received')->sum('amount'),
            'total_staff'         => User::whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher'])->where('is_active', true)->count(),
            'total_facilities'    => Facility::where('is_active', true)->count(),
            'pending_maintenance' => MaintenanceRequest::where('status', 'pending')->count(),
        ];

        return view('reports.index', compact('summary'));
    }

    public function childrenReport(Request $request)
    {
        $children = Child::with(['currentRoomAssignment.roomAllocation.facility', 'admittedBy'])
            ->when($request->filled('gender'), fn($q) => $q->where('gender', $request->gender))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->filled('from'), fn($q) => $q->whereDate('admission_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('admission_date', '<=', $request->to))
            ->orderBy('name')
            ->paginate(25);

        $stats = [
            'total'        => Child::where('is_active', true)->count(),
            'male'         => Child::where('is_active', true)->where('gender', 'male')->count(),
            'female'       => Child::where('is_active', true)->where('gender', 'female')->count(),
            'recent_30d'   => Child::where('is_active', true)->where('admission_date', '>=', now()->subDays(30))->count(),
        ];

        return view('reports.children', compact('children', 'stats'));
    }

    public function donationsReport(Request $request)
    {
        $query = Donation::with(['donor', 'recordedBy'])
            ->when($request->filled('type'), fn($q) => $q->where('donation_type', $request->type))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn($q) => $q->whereDate('donation_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('donation_date', '<=', $request->to));

        $donations = $query->orderBy('donation_date', 'desc')->paginate(25);

        $stats = [
            'total_received'   => Donation::where('status', 'received')->sum('amount'),
            'this_year'        => Donation::where('status', 'received')->whereYear('donation_date', now()->year)->sum('amount'),
            'this_month'       => Donation::where('status', 'received')->whereYear('donation_date', now()->year)->whereMonth('donation_date', now()->month)->sum('amount'),
            'total_count'      => Donation::count(),
            'by_type'          => Donation::where('status', 'received')->selectRaw('donation_type, SUM(amount) as total')->groupBy('donation_type')->pluck('total', 'donation_type'),
        ];

        return view('reports.donations', compact('donations', 'stats'));
    }

    public function staffReport(Request $request)
    {
        $staff = User::with('staffProfile')
            ->whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher', 'manager'])
            ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->status === 'active'))
            ->orderBy('name')
            ->paginate(25);

        $stats = [
            'total'      => User::whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher', 'manager'])->count(),
            'active'     => User::whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher', 'manager'])->where('is_active', true)->count(),
            'by_role'    => User::whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher', 'manager'])->selectRaw('role, COUNT(*) as count')->groupBy('role')->pluck('count', 'role'),
        ];

        return view('reports.staff', compact('staff', 'stats'));
    }

    public function facilitiesReport(Request $request)
    {
        $facilities = Facility::with(['roomAllocations', 'maintenanceRequests'])
            ->withCount(['roomAllocations', 'maintenanceRequests'])
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->status === 'active'))
            ->orderBy('name')
            ->paginate(25);

        $stats = [
            'total'       => Facility::count(),
            'active'      => Facility::where('is_active', true)->count(),
            'by_type'     => Facility::selectRaw('type, COUNT(*) as count')->groupBy('type')->pluck('count', 'type'),
        ];

        return view('reports.facilities', compact('facilities', 'stats'));
    }

    public function maintenanceReport(Request $request)
    {
        $requests = MaintenanceRequest::with(['facility', 'requestedBy', 'assignedTo'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn($q) => $q->where('priority', $request->priority))
            ->when($request->filled('facility_id'), fn($q) => $q->where('facility_id', $request->facility_id))
            ->when($request->filled('from'), fn($q) => $q->whereDate('requested_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('requested_date', '<=', $request->to))
            ->orderBy('requested_date', 'desc')
            ->paginate(25);

        $stats = [
            'total'       => MaintenanceRequest::count(),
            'pending'     => MaintenanceRequest::where('status', 'pending')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'completed'   => MaintenanceRequest::where('status', 'completed')->count(),
            'overdue'     => MaintenanceRequest::where('status', '!=', 'completed')->where('due_date', '<', now())->count(),
            'total_cost'  => MaintenanceRequest::where('status', 'completed')->sum('actual_cost'),
        ];

        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('reports.maintenance', compact('requests', 'stats', 'facilities'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:children,donations,staff,facilities,maintenance',
            'format'      => 'required|in:csv,json',
            'from'        => 'nullable|date',
            'to'          => 'nullable|date',
        ]);

        $data = match ($validated['report_type']) {
            'children'    => Child::where('is_active', true)->get(['id', 'name', 'gender', 'date_of_birth', 'admission_date', 'admission_source']),
            'donations'   => Donation::with('donor:id,name')->where('status', 'received')->get(['id', 'donor_id', 'donation_type', 'amount', 'donation_date', 'receipt_number']),
            'staff'       => User::whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher'])->get(['id', 'name', 'email', 'role', 'is_active']),
            'facilities'  => Facility::get(['id', 'name', 'type', 'capacity', 'is_active']),
            'maintenance' => MaintenanceRequest::with('facility:id,name')->get(['id', 'facility_id', 'title', 'priority', 'status', 'requested_date', 'actual_cost']),
        };

        $filename = $validated['report_type'] . '_report_' . now()->format('Ymd_His');

        if ($validated['format'] === 'json') {
            return response()->json($data)
                ->header('Content-Disposition', "attachment; filename={$filename}.json");
        }

        $csv = $data->map(fn($row) => $row->toArray());
        $headers = $csv->first() ? array_keys($csv->first()) : [];
        $output  = implode(',', $headers) . "\n";
        foreach ($csv as $row) {
            $output .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v ?? '') . '"', $row)) . "\n";
        }

        return response($output, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ]);
    }

    public function exportChildProfile(Child $child)
    {
        $child->load(['admittedBy', 'educationHistories', 'talentsInterests', 'milestones', 'currentRoomAssignment.roomAllocation.facility']);
        return view('reports.child-profile-pdf', compact('child'));
    }

    public function exportDonorReport(Donor $donor)
    {
        $donor->load(['donations' => fn($q) => $q->orderBy('donation_date', 'desc'), 'managedBy']);
        return view('reports.donor-pdf', compact('donor'));
    }
}
