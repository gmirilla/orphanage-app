<?php

namespace App\Http\Controllers;

use App\Models\ChildRoomAssignment;
use App\Models\Facility;
use App\Models\Child;
use App\Models\RoomAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoomAllocationController extends Controller
{
    public function index()
    {
        $rooms = RoomAllocation::with('facility')
            ->withCount(['childAssignments as current_occupants' => fn($q) => $q->whereNull('unassigned_date')])
            ->orderBy('facility_id')
            ->orderBy('room_number')
            ->paginate(20);

        return view('rooms.index', compact('rooms'));
    }

    public function create(Request $request)
    {
        $facilityId = $request->input('facilityid');
        $facility = Facility::find($facilityId);
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('rooms.create', compact('facility', 'facilities'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_number' => 'required|string|max:255',
            'bed_count'   => 'required|integer|min:1',
            'is_active'   => 'required|boolean',
            'facilityid'  => 'required|exists:facilities,id',
        ]);

        $facility = Facility::findOrFail($validatedData['facilityid']);

        $roomAllocation = RoomAllocation::create([
            'room_number' => $validatedData['room_number'],
            'bed_count'   => $validatedData['bed_count'],
            'is_active'   => $validatedData['is_active'],
            'facility_id' => $validatedData['facilityid'],
        ]);

        // Update facility total capacity
        $facility->capacity = $facility->roomAllocations()->sum('bed_count');
        $facility->save();

        return redirect()->route('rooms.view', $roomAllocation)->with('success', 'Room created successfully.');
    }

    public function show(RoomAllocation $roomAllocation)
    {
        $facility = Facility::findOrFail($roomAllocation->facility_id);
        $roomOccupants = ChildRoomAssignment::with('child')
            ->where('room_allocation_id', $roomAllocation->id)
            ->orderBy('assigned_date', 'desc')
            ->get();
        $children = Child::where('is_active', true)->orderBy('name')->get();

        return view('rooms.view', compact('roomAllocation', 'facility', 'roomOccupants', 'children'));
    }

    public function edit(RoomAllocation $roomAllocation)
    {
        $facility = Facility::findOrFail($roomAllocation->facility_id);
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('rooms.edit', compact('roomAllocation', 'facility', 'facilities'));
    }

    public function update(Request $request, RoomAllocation $roomAllocation)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255',
            'bed_count'   => 'required|integer|min:1',
            'is_active'   => 'required|boolean',
        ]);

        try {
            $roomAllocation->update($validated);

            // Re-sync facility capacity
            $facility = $roomAllocation->facility;
            $facility->capacity = $facility->roomAllocations()->sum('bed_count');
            $facility->save();

            return redirect()->route('rooms.view', $roomAllocation)->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update room. Please try again.');
        }
    }

    public function destroy(RoomAllocation $roomAllocation)
    {
        try {
            // Check if room has active occupants
            $activeOccupants = ChildRoomAssignment::where('room_allocation_id', $roomAllocation->id)
                ->whereNull('unassigned_date')
                ->count();

            if ($activeOccupants > 0) {
                return back()->with('error', 'Cannot deactivate a room with active occupants. Please reassign children first.');
            }

            $facility = $roomAllocation->facility;

            // Soft deactivate instead of delete
            $roomAllocation->update(['is_active' => false]);

            // Re-sync facility capacity
            $facility->capacity = $facility->roomAllocations()->where('is_active', true)->sum('bed_count');
            $facility->save();

            return redirect()->route('facilities.show', $facility)->with('success', 'Room deactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Error deactivating room: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate room. Please try again.');
        }
    }

    public function assignChild(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'childid'            => 'required|exists:children,id',
            'room_allocation_id' => 'required|exists:room_allocations,id',
        ]);

        $roomAllocation = RoomAllocation::findOrFail($validatedData['room_allocation_id']);

        if ($roomAllocation->available_beds <= 0) {
            return back()->withErrors(['room_full' => 'The selected room is full. Please choose another room.']);
        }

        $existingAssignment = ChildRoomAssignment::where('child_id', $validatedData['childid'])
            ->whereNull('unassigned_date')
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['child_already_assigned' => 'The selected child is already assigned to a room.']);
        }

        ChildRoomAssignment::create([
            'child_id'           => $validatedData['childid'],
            'room_allocation_id' => $validatedData['room_allocation_id'],
            'assigned_date'      => now()->toDateString(),
            'assigned_by'        => $user->id,
        ]);

        $roomAllocation->increment('occupied_beds');

        return back()->with('success', 'Child assigned to room successfully.');
    }

    public function unassignChild(Request $request)
    {
        $validatedData = $request->validate([
            'childid'            => 'required|exists:children,id',
            'room_allocation_id' => 'required|exists:room_allocations,id',
        ]);

        $assignment = ChildRoomAssignment::where('child_id', $validatedData['childid'])
            ->whereNull('unassigned_date')
            ->first();

        if (!$assignment) {
            return back()->withErrors(['not_assigned' => 'Child is not currently assigned to any room.']);
        }

        $assignment->update(['unassigned_date' => now()->toDateString()]);

        $roomAllocation = RoomAllocation::findOrFail($validatedData['room_allocation_id']);
        if ($roomAllocation->occupied_beds > 0) {
            $roomAllocation->decrement('occupied_beds');
        }

        return back()->with('success', 'Child unassigned from room successfully.');
    }
}
