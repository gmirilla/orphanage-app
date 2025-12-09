
@section('title', 'facility Management')

<x-layouts.app>
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Facility Management</h2>
            <p class="text-sm text-neutral-600">Manage Facility records</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('facilities.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Add New Facility
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
                <label for="type" class="form-label">Facility Type</label>
                <select id="type" name="type" class="form-input w-full">
                    <option value="">All Types</option>
                    @forelse ($types as $type )
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @empty
                        
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
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('facilities.index') }}" class="btn btn-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
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
                    <p class="text-sm text-neutral-600">Total Beds</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $facilities->total() }}</p>
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
                    <p class="text-sm text-neutral-600">Recent Admitted</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $facilities->where('admission_date', '>=', now()->subMonths(3))->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- facility Table -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900">Facility List</h3>
        </div>
        
        @if($facilities->count() > 0)
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Capacity</th>
                        <th>Managed By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $facility)
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium text-neutral-900">{{ $facility->name }}</p>
                                <p class="text-sm text-neutral-600">ID: #{{ $facility->id }}</p>
                            </div>
                        </td>
                        <td>{{ $facility->type }}</td>
                        <td>{{ $facility->description }}</td>
                        <td>{{ $facility->capacity }}</td>
                        <td>{{ $facility->managed_by }}</td>
                        <td>
                            @if($facility->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('facilities.show', $facility) }}" 
                                   class="p-1 btn btn-primary"
                                   title="View Profile">View
                                </a>
                                <a href="{{ route('facilities.edit', $facility) }}" 
                                class="p-1 btn btn-success"
                                   title="Edit">Edit
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <button class="p-1 btn btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-bs-toDelete="{{$facility->id}}"
                                        title="Delete">Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-neutral-200">
            {{ $facilities->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="users" class="w-16 h-16 text-neutral-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No facility found</h3>
            <p class="text-neutral-600 mb-6">Get started by adding the first Facility.</p>
            <a href="{{ route('facilities.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Add Facility
            </a>
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
    //childToDelete = childId;
    document.getElementById('childName').textContent = childName;
    document.getElementById('deleteModal').classList.remove('hidden');
}


function deleteFacility() {
    if (!childToDelete) return;
    
    // Create a form to submit the DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/facility/${childToDelete}`;
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    
    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.app>