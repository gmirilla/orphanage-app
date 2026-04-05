<x-layouts.app>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Volunteers</h2>
            <p class="text-sm text-neutral-600">Manage volunteer registrations and tasks</p>
        </div>
        <a href="{{ route('volunteers.create') }}" class="btn btn-primary mt-4 sm:mt-0">
            <i class="fa fa-plus mr-2"></i> Add Volunteer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="form-input w-full">
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-semibold">Volunteer List ({{ $volunteers->total() }})</h3>
        </div>

        @if($volunteers->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Approval Status</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($volunteers as $volunteer)
                    <tr>
                        <td>
                            <p class="font-medium text-neutral-900">{{ $volunteer->name }}</p>
                            <p class="text-sm text-neutral-500">ID: {{ $volunteer->volunteerProfile->volunteer_id ?? '—' }}</p>
                        </td>
                        <td>{{ $volunteer->email }}</td>
                        <td>{{ $volunteer->phone ?? '—' }}</td>
                        <td>
                            @php $status = $volunteer->volunteerProfile->status ?? 'pending'; @endphp
                            <span class="badge {{ $status === 'approved' ? 'badge-success' : ($status === 'suspended' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $volunteer->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $volunteer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('volunteers.show', $volunteer) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('volunteers.edit', $volunteer) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @if(($volunteer->volunteerProfile->status ?? '') !== 'approved')
                                <form method="POST" action="{{ route('volunteers.approve', $volunteer) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('volunteers.destroy', $volunteer) }}" onsubmit="return confirm('Deactivate this volunteer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $volunteers->links() }}</div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="users" class="w-16 h-16 text-neutral-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No volunteers found</h3>
            <p class="text-neutral-600 mb-6">Get started by adding the first volunteer.</p>
            <a href="{{ route('volunteers.create') }}" class="btn btn-primary">Add Volunteer</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
