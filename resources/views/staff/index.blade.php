@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Staff Management</h2>
            <p class="text-sm text-neutral-600">Manage staff profiles, schedules, and performance</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Add New Staff
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="form-label">Search Staff</label>
                <input type="text" 
                       id="search"
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name, email, phone..."
                       class="form-input w-full">
            </div>
            
            <div>
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-input w-full">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-input w-full">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
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
                    <p class="text-sm text-neutral-600">Total Staff</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $staff->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Administrators</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $staff->where('role', 'admin')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="heart" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Caregivers</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $staff->where('role', 'caregiver')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="stethoscope" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Medical Staff</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $staff->whereIn('role', ['nurse', 'teacher'])->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900">Staff List</h3>
        </div>
        
        @if($staff->count() > 0)
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                    <tr>
                        <td>
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-500 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        </td>
                        <td>
                            <div>
                                <p class="font-medium text-neutral-900">{{ $member->name }}</p>
                                <p class="text-sm text-neutral-600">ID: #{{ $member->id }}</p>
                            </div>
                        </td>
                        <td class="text-sm text-neutral-600">{{ $member->email }}</td>
                        <td>
                            <span class="badge badge-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'caregiver' ? 'info' : 'primary') }}">
                                {{ ucfirst($member->role) }}
                            </span>
                        </td>
                        <td class="text-sm text-neutral-600">
                            {{ $member->staffProfile?->department ?? 'Not Set' }}
                        </td>
                        <td class="text-sm text-neutral-600">
                            {{ $member->staffProfile?->position ?? 'Not Set' }}
                        </td>
                        <td class="text-sm text-neutral-600">
                            {{ $member->staffProfile?->date_hired?->format('M d, Y') ?? 'Not Set' }}
                        </td>
                        <td>
                            @if($member->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('staff.show', $member) }}" 
                                   class="text-primary-600 hover:text-primary-800 p-1"
                                   title="View Profile">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('staff.shifts', $member) }}" 
                                   class="text-green-600 hover:text-green-800 p-1"
                                   title="View Shifts">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('staff.edit', $member) }}" 
                                   class="text-blue-600 hover:text-blue-800 p-1"
                                   title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <button onclick="confirmDelete({{ $member->id }}, '{{ $member->name }}')" 
                                        class="text-red-600 hover:text-red-800 p-1"
                                        title="Deactivate">
                                    <i data-lucide="user-x" class="w-4 h-4"></i>
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
            {{ $staff->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="users" class="w-16 h-16 text-neutral-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No staff members found</h3>
            <p class="text-neutral-600 mb-6">Get started by adding the first staff member to your orphanage.</p>
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Add First Staff Member
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-neutral-900 mb-4">Confirm Deactivation</h3>
        <p class="text-neutral-600 mb-6">Are you sure you want to deactivate <span id="staffName" class="font-medium"></span>? They will no longer be able to access the system.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="deleteStaff()" class="btn btn-danger">Deactivate</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let staffToDelete = null;

function confirmDelete(staffId, staffName) {
    staffToDelete = staffId;
    document.getElementById('staffName').textContent = staffName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    staffToDelete = null;
}

function deleteStaff() {
    if (!staffToDelete) return;
    
    // Create a form to submit the DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/staff/${staffToDelete}`;
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
@endpush
@endsection