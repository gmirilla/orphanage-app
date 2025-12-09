<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Child;
use App\Models\ChildRoomAssignment;
use App\Models\Donor;
use App\Models\Donation;
use App\Models\MaintenanceRequest;
use App\Models\RoomAllocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities.
     */
    public function index(Request $request)
    {
        $query = Facility::query();

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $facilities = $query->orderBy('name')->paginate(15);

        $types = ['dormitory' => 'Dormitory', 'classroom' => 'Classroom', 'kitchen' => 'Kitchen', 'clinic' => 'Clinic', 'office' => 'Office', 'recreation' => 'Recreation', 'storage' => 'Storage'];

        return view('facilities.index', compact('facilities', 'types'));
    }

    /**
     * Show the form for creating a new facility.
     */
    public function create()
    {
        $types = ['dormitory' => 'Dormitory', 'classroom' => 'Classroom', 'kitchen' => 'Kitchen', 'clinic' => 'Clinic', 'office' => 'Office', 'recreation' => 'Recreation', 'storage' => 'Storage'];
        $admins = User::where('role', 'admin')->where('is_active', true)->orderBy('name')->get();
        return view('facilities.create', compact('types', 'admins'));
    }

    /**
     * Store a newly created facility in storage.
     */
    public function store(Request $request)
    {

        $user=Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dormitory,classroom,kitchen,clinic,office,recreation,storage',
            'capacity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'admin_id' => 'nullable|integer|exists:users,id',
        ]);


        try {
            DB::beginTransaction();


            Facility::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'type_error' => $validated['type_typeerror'],
                'capacity' => $validated['capacity'],
                'description' => $validated['description'],
                'managed_by' => $validated['admin_id'],
                'is_active' => true,
            ]);
                  

            DB::commit();

            return redirect()->route('facilities.index')->with('success', 'Facility created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating facility: ' . $e->getMessage());
              $errormsg = $e->getMessage();
            return back()->withInput()->with('error', 'Failed to create facility. Please try again.',$errormsg);
        }
    }

    /**
     * Display the specified facility.
     */
    public function show(Facility $facility)
    {
        $facility->load([
            'managedBy',
            'roomAllocations' => function ($query) {
                $query->withCount('children');
            },
            'maintenanceRequests' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ]);

        // Calculate statistics
        $totalRooms = $facility->roomAllocations->count();
        $occupiedRooms = $facility->roomAllocations->whereHas('children')->count();
        $totalOccupancy = $facility->roomAllocations->sum('children_count');
        $maintenanceRequests = $facility->maintenanceRequests->count();
        $pendingMaintenance = $facility->maintenanceRequests->where('status', 'pending')->count();

        return view('facilities.show', compact('facility', 'totalRooms', 'occupiedRooms', 'totalOccupancy', 'maintenanceRequests', 'pendingMaintenance'));
    }

    /**
     * Show the form for editing the specified facility.
     */
    public function edit(\App\Models\Facility $facility)
    {
        $types = ['dormitory' => 'Dormitory', 'classroom' => 'Classroom', 'kitchen' => 'Kitchen', 'clinic' => 'Clinic', 'office' => 'Office', 'recreation' => 'Recreation', 'storage' => 'Storage'];
        $admins = User::where('role', 'admin')->where('is_active', true)->orderBy('name')->get();
        return view('facilities.edit', compact('facility', 'types', 'admins'));
    }

    /**
     * Update the specified facility in storage.
     */
    public function update(Request $request, \App\Models\Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dormitory,classroom,kitchen,clinic,office,recreation,storage',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'condition' => 'nullable|in:excellent,good,fair,poor',
            'construction_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $facility->update($validated);

            DB::commit();

            return redirect()->route('facilities.index')->with('success', 'Facility updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating facility: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update facility. Please try again.');
        }
    }

    /**
     * Remove the specified facility from storage.
     */
    public function destroy(\App\Models\Facility $facility)
    {
        try {
            DB::beginTransaction();

            // Soft delete - deactivate instead of deleting
            $facility->update(['is_active' => false]);

            DB::commit();

            return redirect()->route('facilities.index')->with('success', 'Facility deactivated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deactivating facility: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate facility. Please try again.');
        }
    }

    /**
     * Show rooms for a facility.
     */
    public function rooms(Facility $facility)
    {
        $rooms = $facility->roomAllocations()
            ->withCount('children')
            ->orderBy('room_number')
            ->paginate(20);

        $availableChildren = Child::where('is_active', true)
            ->whereDoesntHave('currentRoomAssignment')
            ->orderBy('name')
            ->get();

        return view('facilities.rooms', compact('facility', 'rooms', 'availableChildren'));
    }

    /**
     * Assign a room to a facility.
     */
    public function assignRoom(Request $request, \App\Models\Facility $facility)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:room_allocations,room_number,NULL,id,facility_id,' . $facility->id,
            'room_type' => 'required|in:bedroom,bathroom,kitchen,office,storage,common_area',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        try {
            RoomAllocation::create([
                'facility_id' => $facility->id,
                'room_number' => $validated['room_number'],
                'room_type' => $validated['room_type'],
                'capacity' => $validated['capacity'],
                'description' => $validated['description'],
                'is_active' => true,
            ]);

            return back()->with('success', 'Room assigned to facility successfully!');
        } catch (\Exception $e) {
            Log::error('Error assigning room: ' . $e->getMessage());
            return back()->with('error', 'Failed to assign room. Please try again.');
        }
    }

    /**
     * Search facilities via API.
     */
    public function search(Request $request)
    {
        $query = \App\Models\Facility::where('is_active', true);

        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $facilities = $query->orderBy('name')->limit(10)->get();

        return response()->json($facilities);
    }
}
