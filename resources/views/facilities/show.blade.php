@section('title', 'Add New Facility')

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
</div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <div class="flex items-center space-x-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-neutral-900">{{ $facility->name }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span
                            class="badge badge-{{ $facility->type === 'dormitory' ? 'info' : 'primary' }}">{{ ucfirst($facility->type) }}</span>

                        @if ($facility->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-secondary">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- facility Information -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <div class="flex justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Facility Information</h3>
                    <div><button type="button" onclick="openMaintenanceModal()" class="btn btn-sm btn-primary">
                            <i data-lucide="plus" class="w-4 h-4"></i> Maintenance Request</button></div>
                </div>
                <div class="space-y-3 table-responsive">
                    <table class="table-striped table">
                        <tbody>
                            <tr>
                                <td><b>Facility Name</b></td>
                                <td>{{ $facility->name }}</td>
                            </tr>
                            <tr>
                                <td><b>Facility Type</b></td>
                                <td>{{ ucfirst($facility->type) }}</td>
                            </tr>
                            <tr>
                                <td><b>Description</b></td>
                                <td>{{ $facility->description ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><b>Capacity</b></td>
                                <td>{{ $facility->capacity ?? 'Not recorded' }}</td>
                            </tr>
                            <tr>
                                <td><b>Number of Rooms</b></td>
                                <td>{{ $facility->roomAllocations()->count() }}</td>
                            </tr>
                            <tr>
                                <td><b>Occupied Beds</b></td>
                                <td>{{ $facility->roomAllocations()->sum('occupied_beds') ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><b>Total Beds</b></td>
                                <td>{{ $facility->roomAllocations()->sum('bed_count') ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><b>Pending Maintenance Request:</b></td>
                                <td>{{ $pendingMaintenance }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Managed By</label>
                        <p class="text-neutral-900">{{ $facility->managedBy->name }}</p>
                    </div>
                </div>
            </div>



            <!-- List of rooms -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Rooms in {{ $facility->name }}</h3>
                <div class="overflow-auto max-h-96">
                    @if ($facility->roomAllocations->isEmpty())
                        <p class="text-neutral-700">No rooms allocated in this facility.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($facility->roomAllocations as $roomallocation)
                                <li class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="text-md font-medium text-neutral-900">Room
                                                {{ $roomallocation->room_number }}</h4>
                                            <p class="text-sm text-neutral-700">Capacity:
                                                {{ $roomallocation->bed_count }}</p>
                                            <p class="text-sm text-neutral-700">Occupied Beds:
                                                {{ $roomallocation->occupied_beds ?? 0 }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('rooms.view', $roomallocation) }}"
                                                class="btn btn-sm btn-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
                <div class="mb-3 text-center">
                    <form action="{{ route('rooms.create') }}" method="post">
                        @csrf
                        <input type="text" id="facilityid" name="facilityid" hidden value='{{ $facility->id }}'>
                        <button type="submit" class="btn btn-primary mt-4">Allocate New Room</button>
                    </form>
                </div>




            </div>
        </div>

        <!-- Maintenance Request -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <div class="flex items-center space-x-6">
                <h3 class="text-lg font-semibold text-neutral-900">Maintenance Requests</h3>
                <button type="button" onclick="openMaintenanceModal()" class="btn btn-primary">
                    New Maintenance Request
                </button>
            </div>
            <div class="table-responsive mt-4">
                <table class="table-striped table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facility->maintenanceRequests as $request)
                            <tr>
                                <td>{{ $request->title }}</td>
                                <td>{{ $request->description }}</td>
                                <td>{{ ucfirst($request->priority) }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td>{{ $request->requested_date->format('M d, Y') }}</td>
                                <td><a href="{{ route('maintenance.view', $request) }}" class="btn btn-sm btn-secondary">View Details</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-neutral-700">No maintenance requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>

<!-- Maintenance Request Modal -->
<div id="maintenanceRequestModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-lg font-semibold text-zinc-900">New Maintenance Request</h3>
            <button onclick="closeMaintenanceModal()" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('maintenance.new') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <div class="p-6 space-y-4">
                <div>
                    <label for="issue_description" class="form-label">Issue Description</label>
                    <textarea id="issue_description" name="description" rows="3" required class="form-input"></textarea>
                </div>
                <div>
                    <label for="priority_level" class="form-label">Priority Level</label>
                    <select id="priority_level" name="priority" required class="form-input">
                        <option value="">Select priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeMaintenanceModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
function openMaintenanceModal() {
    document.getElementById('maintenanceRequestModal').classList.remove('hidden');
}
function closeMaintenanceModal() {
    document.getElementById('maintenanceRequestModal').classList.add('hidden');
}
document.getElementById('maintenanceRequestModal').addEventListener('click', function(e) {
    if (e.target === this) closeMaintenanceModal();
});
</script>
        
</x-layouts.app>
