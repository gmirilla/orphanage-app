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
                <input type="text" name="description" id="facilityname" class="form-input"
                    value="{{ old('description', $facility->description ?? '') }}" disabled>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Facility Type -->
            <div class="mb-3">
                <label for="type" class="form-label">Facility Type</label>
                <input type="text" name="type" id ="type" class="form-input" value="{{ $facility->type }}"
                    disabled>

                @error('type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>



            <!-- Room Number -->

            <div class="mb-3">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-input"
                    value="{{ $roomAllocation->room_number }}" placeholder="Enter Room Number or Description" disabled>
            </div>
            <!-- Bed Count-->
            <div class="mb-3">
                <label for="bed_count" class="form-label">Bed Count</label>
                <input type="number" name="bed_count" id="bed_count" class="form-input"
                    value="{{ $roomAllocation->bed_count }}" placeholder="How many Beds available in Room" disabled>
            </div>
            <!-- Occupants Count-->
            <div class="mb-3">
                <label for="occupied_beds" class="form-label">Occupied Beds</label>
                <input type="number" name="occupied_beds" id="occupied_beds" class="form-input"
                    value="{{ $roomAllocation->occupied_beds ?? 0 }}" disabled>
            </div>
            <!-- Is Room Active-->
            <div class="mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                @if ($roomAllocation->is_active == 1)
                    <div class="form-input" disabled>Yes</div> <button type="button"
                        onclick="updateRoom({{ $roomAllocation->id }})" class="btn btn-sm btn-danger">Make
                        Inactive</button>
                @else
                    <div class="form-input" disabled>No</div> <button type="button"
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
                <button type="button" onclick="openAssignModal()" class="btn btn-primary">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Assign Child
                </button>
            </div>

            <!-- Modal for Assigning Child to Room -->
            <div id="roomAssignModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between p-6 border-b border-zinc-100">
                        <h3 class="text-lg font-semibold text-zinc-900">Assign Child to Room</h3>
                        <button onclick="closeAssignModal()" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('rooms.assignChild', $roomAllocation->id) }}">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="form-label" for="childid">Select Child</label>
                                <select name="childid" required class="form-input" id="childid">
                                    <option value="">— Choose a child —</option>
                                    @foreach ($children as $child)
                                        @if (empty($child->currentRoomAssignment))
                                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="room_allocation_id" value="{{ $roomAllocation->id }}">
                        </div>
                        <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                            <button type="button" onclick="closeAssignModal()" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Assign to Room</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            function openAssignModal() {
                document.getElementById('roomAssignModal').classList.remove('hidden');
            }
            function closeAssignModal() {
                document.getElementById('roomAssignModal').classList.add('hidden');
            }
            document.getElementById('roomAssignModal').addEventListener('click', function(e) {
                if (e.target === this) closeAssignModal();
            });
            </script>



</x-layouts.app>
