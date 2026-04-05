
@section('title', 'Maintenance Management')

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
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Maintenance Management</h2>
            <p class="text-sm text-neutral-600">Manage Maintenance records</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                <i class="fa fa-plus mr-2"></i>
                Add New Request
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="form-label">Search facility</label>
                <input type="text" 
                       id="search"
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name..."
                       class="form-input w-full">
            </div>
            
            <div>
                <label for="type" class="form-label"> Priority</label>
                <select id="type" name="type" class="form-input w-full">
                    <option value="">All Level</option>
                    @forelse ($plevels as $type )
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @empty
                    <option value="" disabled> No types available</option>
                        
                    @endforelse
                </select>
            </div>
            
            <div>
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-input w-full">
                    <option value="">All facilities</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('facilities.index') }}" class="btn btn-secondary mx-2">
                    <i class="fa fa-times mr-2"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Total No of Facilities</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $facilities->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5 text-pink-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Occupancy Rate</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $facilities->where('gender', 'male')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">PendingMaintenance Requests</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $facilities->where('gender', 'female')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Recent Admission</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $facilities->where('admission_date', '>=', now()->subMonths(3))->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Table -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200 flex justify-between">
            <h3 class="text-lg font-semibold text-neutral-900">Request List </h3>
                        <button type="button"  class="btn btn-primary" data-bs-target="#newMaintenanceRequest">
                <i class="fa fa-plus w-4 h-4 mr-2"></i>
                Add Maintenance Request
            </button>
        </div>
        
        @if($requests->count() > 0)
        <div class="overflow-x-auto">

            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Requested By</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium text-neutral-900">{{ $request->title }}</p>
                                <p class="text-sm text-neutral-600">ID: #{{ $request->id }}</p>
                            </div>
                        </td>
                        <td>{{ $request->facility->type }}</td>
                        <td>{{ $request->description }}</td>
                        <td>{{ ucfirst($request->priority) }}</td>
                        <td>{{ $request->requestedBy->name }}</td>
                        <td>{{ $request->assignedTo->name ?? 'Unassigned'}}</td>
                        <td>
                            @if($request->status =='completed')
                                <span class="badge badge-success">{{ucfirst($request->status)}}</span>
                            @else
                                <span class="badge badge-warning">{{ucfirst($request->status)}} </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('maintenance.view', $request) }}" 
                                   class="p-1 btn btn-primary"
                                   title="View Profile">View
                                </a>
                                <a href="{{ route('maintenance.edit_request', $request) }}" 
                                class="p-1 btn btn-success"
                                   title="Edit">Edit
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <button class="p-1 btn btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-bs-toDelete="{{$request->id}}"
                                        title="Delete">Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="users" class="w-16 h-16 text-neutral-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No Maintenance Request found</h3>
            <p class="text-neutral-600 mb-6">Get started by adding the first Maintenance Request.</p>
            <button type="button"  class="btn btn-primary" data-bs-target="#newMaintenanceRequest">
                <i class="fa fa-plusw-4 h-4 mr-2"></i>
                Add Maintenance Request
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">Confirm Deletion
                    </div>
                    <div class="modal-body">
                        <p class="text-neutral-600 mb-6">Are you sure you want to delete this record ? <br/>
                            <span id="facilityName" class="font-medium"></span>This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <div class="flex justify-end space-x-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="deleteFacility()" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
                </div>
</div>



<!-- New Maintenance Request Modal -->
<div class="modal fade" id="newMaintenanceRequest" tabindex="-1" aria-labelledby="newMaintenanceRequestLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">New Maintenance Request
                    </div>
                    <div class="modal-body">
                        <p class="text-neutral-600 mb-6">Are you sure you want to delete this record ? <br/>
                            <span id="facilityName" class="font-medium"></span>This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <div class="flex justify-end space-x-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="deleteFacility()" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
                </div>
</div>


<script>
var childToDelete = null;
var deleteModal = document.getElementById('deleteModal')
deleteModal.addEventListener('show.bs.modal', function(event) {
                    // Button that triggered the modal
                var button = event.relatedTarget
                // Extract info from data-bs-* attributes
                var childToDeleteID = button.getAttribute('data-bs-toDelete')
                childToDelete = childToDeleteID;
});

function confirmDelete(childId, childName) {

}


function deleteFacility() {
    if (!childToDelete) return;
    
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.app>