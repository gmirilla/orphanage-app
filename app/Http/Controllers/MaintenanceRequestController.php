<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $isPrivileged = in_array($user->role, ['admin', 'manager']);

        $requests = MaintenanceRequest::with('facility')
            ->when(!$isPrivileged, function ($query) use ($user) {
                $query->where('requested_by', $user->id);
            })
            ->latest()
            ->get();

        $facilities = Facility::when(!$isPrivileged, function ($query) use ($user) {
            $query->whereHas('maintenanceRequests', function ($q) use ($user) {
                $q->where('requested_by', $user->id);
            });
        })
            ->get();
        $plevels = MaintenanceRequest::select('priority')->distinct()->pluck('priority');
        return view('maintenance.index', compact('requests', 'plevels', 'facilities'));
    }

    public function newRequest(Request $request)
    {

        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'required|string',
        ]);


        $user = Auth::user();

        $maintenanceRequest = MaintenanceRequest::create([
            'facility_id' => $request->input('facility_id'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'requested_by' => $user->id,
            'title' => 'Maintenance Request for ' . $request->description,
            'priority' => $request->priority,
            'requested_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Maintenance request submitted successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function view(MaintenanceRequest $maintenanceRequest)
    {
        //
        $maintenanceRequest->load('facility', 'requestedBy', 'assignedTo');
      
        
        return view('maintenance.show', compact('maintenanceRequest'));
    }


        /**
     * Show the form for editing the specified resource.
     */
    public function editRequest(MaintenanceRequest $maintenanceRequest)
    {
        //
        $users= User::all();
        return view('maintenance.edit', compact('maintenanceRequest', 'users'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 public function updateStatus(Request $request, MaintenanceRequest $maintenanceRequest)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,in_progress,completed',
        'priority' => 'required|in:low,medium,high',
        'resolution' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
        'due_date' => 'nullable|date',
        'estimated_cost' => 'nullable|numeric',
        'actual_cost' => 'nullable|numeric',
    ]);

    $maintenanceRequest->update($validated);

    return redirect()
        ->route('maintenance.index')
        ->with('success', 'Maintenance request status updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        //
    }
}
