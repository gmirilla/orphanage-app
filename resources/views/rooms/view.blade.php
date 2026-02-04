@section('title', 'Add New Room')

<x-layouts.app>
    <div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container">
            <h2>Room: <i>{{ $roomAllocation->room_number }}</i></h2>


            <!-- Room Details -->
            <div class="mb-3">
                <label for="name" class="form-label" required>Facility Name</label>
                <input type="text" name="description" id="facilityname" class="form-control"
                    value="{{ old('description', $facility->description ?? '') }}" disabled>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Facility Type -->
            <div class="mb-3">
                <label for="type" class="form-label">Facility Type</label>
                <input type="text" name="type" id ="type" class="form-control" value="{{ $facility->type }}"
                    disabled>

                @error('type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>



            <!-- Room Number -->

            <div class="mb-3">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-control"
                    value="{{ $roomAllocation->room_number }}" placeholder="Enter Room Number or Description" disabled>
            </div>
            <!-- Bed Count-->
            <div class="mb-3">
                <label for="bed_count" class="form-label">Bed Count</label>
                <input type="number" name="bed_count" id="bed_count" class="form-control"
                    value="{{ $roomAllocation->bed_count }}" placeholder="How many Beds available in Room" disabled>
            </div>
            <!-- Occupants Count-->
            <div class="mb-3">
                <label for="occupied_beds" class="form-label">Occupied Beds</label>
                <input type="number" name="occupied_beds" id="occupied_beds" class="form-control"
                    value="{{ $roomAllocation->occupied_beds ?? 0 }}" disabled>
            </div>
            <!-- Is Room Active-->
            <div class="mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                @if ($roomAllocation->is_active == 1)
                    <div class="form-control" disabled>Yes</div> <button type="button"
                        onclick="updateRoom({{ $roomAllocation->id }})" class="btn btn-sm btn-danger">Make
                        Inactive</button>
                @else
                    <div class="form-control" disabled>No</div> <button type="button"
                        onclick="updateRoom({{ $roomAllocation->id }})" class="btn btn-sm btn-success">Make
                        Active</button>
                @endif
                <input type="number" id="facilityid" name="facilityid" value="{{ $facility->id }}" hidden>
            </div>
        </div>
        <!-- section for listing room occupants -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Current Ocuppants Information -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3> Current Occupants</h3>
                <div class="space-y-3 table-responsive">
                    @if ($roomOccupants->isEmpty())
                        <p class="text-neutral-700">No occupants in this room.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($roomOccupants as $occupant)
                                <!-- Only show Current Occupants -->
                                @if (empty($occupant->unassigned_date))
                                    <li class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="text-md font-medium text-neutral-900">
                                                    {{ $occupant->getchild()->name ?? 'Unknown Child' }}</h4>
                                                <p class="text-sm text-neutral-700">Assigned Date:
                                                    {{ $occupant->assigned_date->format('M d, Y') }}</p>
                                                <p class="text-sm text-neutral-700">Unassigned Date:
                                                    @if ($occupant->unassigned_date)
                                                        {{ $occupant->unassigned_date->format('M d, Y') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>





                                            </div>
                                            <div>
                                                <a href="{{ route('children.show', $occupant->child_id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    View Child
                                                </a>
                                                <form action="{{ route('rooms.unassignChild') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="childid"
                                                        value="{{ $occupant->child_id }}">
                                                    <input type="hidden" name="room_allocation_id"
                                                        value="{{ $occupant->room_allocation_id }}">

                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger mt-3">Unassign</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button type="button" class="btn btn-success py-2" data-bs-toggle="modal"
                    data-bs-target="#roomAssignModal" data-bs-whatever=""><i class="fa fa-bed"></i>Assign Child</button>
            </div>

            <!-- Modal for Assigning Child to Room -->
            <div class="modal fade" id="roomAssignModal" tabindex="-1" aria-labelledby="roomAssignModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Child Room Assignment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('rooms.assignChild', $roomAllocation->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <div class="col-auto">
                                        <label class='form-label' for="childid">Child Name</label>
<select name="childid" required class="form-select form-control-lg" id="childid">
    <option value="">Select Child</option>

    @foreach ($children as $child)
        @if (empty($child->currentRoomAssignment)) 
            <option value="{{ $child->id }}">
                {{ $child->name }} |
                <b>{{ $child->currentRoomAssignment->room_number ?? 'Unassigned' }}</b>
            </option>
        @endif
    @endforeach
</select>

                                        <input type="number" id="room_allocation_id" name="room_allocation_id"
                                            value="{{ $roomAllocation->id }}" hidden>
                                        <button type="submit" class="btn btn-primary mt-3">Assign Child to
                                            Room</button>
                                    </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>



</x-layouts.app>
