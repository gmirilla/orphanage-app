<?php

namespace App\Http\Controllers;

use App\Models\ChildRoomAssignment;
use App\Models\Facility;
use App\Models\Child;
use App\Models\RoomAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $facilityId = $request->input('facilityid');
        $facility=Facility::where('id', $facilityId)->first();


        return view('rooms.create', compact('facility'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'room_number' => 'required|string|max:255',
            'bed_count' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'facilityid' => 'required|exists:facilities,id',
        ]);
        $facility=Facility::where('id', $validatedData['facilityid'])->first();
        $roomAllocation = new RoomAllocation();
        $roomAllocation->room_number = $validatedData['room_number'];
        $roomAllocation->bed_count = $validatedData['bed_count'];
        $roomAllocation->is_active = $validatedData['is_active'];
        $roomAllocation->facility_id = $validatedData['facilityid'];
        $roomAllocation->save();

        //update facility capacity 
        $facility->load('roomAllocations');
        $facility->capacity=$facility->roomAllocations()->sum('bed_count');
        $facility->save();

        $roomOccupants=ChildRoomAssignment::where('room_allocation_id', $roomAllocation->id)->orderBy('assigned_date', 'desc')->get();

        return view ('rooms.view', compact('roomAllocation','facility', 'roomOccupants'));

        
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomAllocation $roomAllocation)
    {
        //

        $facility=Facility::where('id', $roomAllocation->facility_id)->first();
        $roomOccupants=ChildRoomAssignment::where('room_allocation_id', $roomAllocation->id)->orderBy('assigned_date', 'desc')->get();
        $children=Child::all();


        return view ('rooms.view', compact('roomAllocation','facility', 'roomOccupants', 'children'));
    }

        /**
     * Assign Children to Rooms.
     */
    public function assignChild(Request $request)
    {
        //
        $user=Auth::user();
        $validatedData = $request->validate([
            'childid' => 'required|exists:children,id',
            'room_allocation_id' => 'required|exists:room_allocations,id',
        ]);
        // Check if Room has available Beds
        $roomAllocation = RoomAllocation::where('id', $validatedData['room_allocation_id'])->first();
        if ($roomAllocation->available_beds <= 0) {
            return back()->withErrors(['room_full' => 'The selected room is full. Please choose another room.']);
        }
        //Check if the Child is already assigned to a room
        $existingAssignment = ChildRoomAssignment::where('child_id', $validatedData['childid'])->whereNull('unassigned_date')->first();
        if ($existingAssignment) {
            return back()->withErrors(['child_already_assigned' => 'The selected child is already assigned to a room.']);
        }
        // Create new assignment
        $childRoomAssignment = new ChildRoomAssignment();
        $childRoomAssignment->child_id = $validatedData['childid'];
        $childRoomAssignment->room_allocation_id = $validatedData['room_allocation_id'];
        $childRoomAssignment->assigned_date = now();
        $childRoomAssignment->assigned_by=$user->id;
        $childRoomAssignment->save();

        // Update room allocation available beds count

        $roomAllocation->occupied_beds += 1;
        $roomAllocation->save();

        return back()->with('success', 'Child assigned to room successfully.');
    }

    //Unassign Child from Room
       public function unassignChild(Request $request)
    {
        //
        $user=Auth::user();
        $validatedData = $request->validate([
            'childid' => 'required|exists:children,id',
            'room_allocation_id' => 'required|exists:room_allocations,id',
        ]);
        // Get Current Room Assignment
        $childroomAllocation = ChildRoomAssignment::where('child_id', $validatedData['childid'])->whereNull('unassigned_date')->first();
        $childroomAllocation->unassigned_date = now();
        $childroomAllocation->save();
        
        // Update Room Allocation Occupied Beds
        $roomAllocation = RoomAllocation::where('id', $validatedData['room_allocation_id'])->first();
        // Update room allocation available beds count
        if ($roomAllocation->occupied_beds>0){
            $roomAllocation->occupied_beds -= 1;
        }

        
        $roomAllocation->save();

        return back()->with('success', 'Child unassigned from room successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomAllocation $roomAllocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomAllocation $roomAllocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomAllocation $roomAllocation)
    {
        //
    }
}
