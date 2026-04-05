<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isPrivileged = in_array($user->role, ['admin', 'manager']);

        $query = MaintenanceRequest::with(['facility', 'requestedBy', 'assignedTo'])
            ->when(!$isPrivileged, fn($q) => $q->where('requested_by', $user->id));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('facility', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('type')) {
            $query->where('priority', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->get();

        $facilities = Facility::when(!$isPrivileged, function ($q) use ($user) {
            $q->whereHas('maintenanceRequests', fn($q2) => $q2->where('requested_by', $user->id));
        })->get();

        $plevels = MaintenanceRequest::select('priority')->distinct()->pluck('priority');

        return view('maintenance.index', compact('requests', 'plevels', 'facilities'));
    }

    public function create()
    {
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();
        $users = User::whereIn('role', ['admin', 'manager', 'caregiver'])->where('is_active', true)->orderBy('name')->get();
        return view('maintenance.create', compact('facilities', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'due_date'    => 'nullable|date|after:today',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        try {
            MaintenanceRequest::create([
                ...$validated,
                'status'         => 'pending',
                'requested_by'   => Auth::id(),
                'requested_date' => now()->toDateString(),
            ]);

            return redirect()->route('maintenance.index')->with('success', 'Maintenance request submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating maintenance request: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit request. Please try again.');
        }
    }

    public function view(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->load('facility', 'requestedBy', 'assignedTo');
        return view('maintenance.show', compact('maintenanceRequest'));
    }

    public function editRequest(MaintenanceRequest $maintenanceRequest)
    {
        $users = User::all();
        return view('maintenance.edit', compact('maintenanceRequest', 'users'));
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $users = User::whereIn('role', ['admin', 'manager', 'caregiver'])->where('is_active', true)->orderBy('name')->get();
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();
        return view('maintenance.edit', compact('maintenanceRequest', 'users', 'facilities'));
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'status'         => 'required|in:pending,in_progress,completed,cancelled',
            'priority'       => 'required|in:low,medium,high,urgent',
            'resolution'     => 'nullable|string',
            'assigned_to'    => 'nullable|exists:users,id',
            'due_date'       => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost'    => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'completed' && !$maintenanceRequest->completed_date) {
            $validated['completed_date'] = now()->toDateString();
        }

        $maintenanceRequest->update($validated);

        return redirect()->route('maintenance.index')->with('success', 'Maintenance request updated successfully.');
    }

    public function assign(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);

        $maintenanceRequest->update([
            'assigned_to' => $validated['assigned_to'],
            'due_date'    => $validated['due_date'] ?? $maintenanceRequest->due_date,
            'status'      => $maintenanceRequest->status === 'pending' ? 'in_progress' : $maintenanceRequest->status,
        ]);

        return back()->with('success', 'Technician assigned successfully.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        try {
            $maintenanceRequest->delete();
            return redirect()->route('maintenance.index')->with('success', 'Maintenance request deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting maintenance request: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete request. Please try again.');
        }
    }

    public function newRequest(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'required|string',
            'priority'    => 'nullable|in:low,medium,high,urgent',
        ]);

        MaintenanceRequest::create([
            'facility_id'    => $request->facility_id,
            'title'          => 'Maintenance Request - ' . now()->format('M d, Y'),
            'description'    => $request->description,
            'priority'       => $request->priority ?? 'medium',
            'status'         => 'pending',
            'requested_by'   => Auth::id(),
            'requested_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Maintenance request submitted successfully.');
    }

    public function getStats()
    {
        $stats = [
            'total'       => MaintenanceRequest::count(),
            'pending'     => MaintenanceRequest::where('status', 'pending')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'completed'   => MaintenanceRequest::where('status', 'completed')->count(),
            'overdue'     => MaintenanceRequest::where('status', '!=', 'completed')->where('due_date', '<', now())->count(),
            'urgent'      => MaintenanceRequest::where('priority', 'urgent')->where('status', '!=', 'completed')->count(),
        ];

        return response()->json($stats);
    }
}
