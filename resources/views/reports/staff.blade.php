<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Staff Report</h2>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
            <p class="text-sm text-neutral-600">Total Staff</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            <p class="text-sm text-neutral-600">Active</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border">
            @foreach($stats['by_role'] as $role => $count)
            <p class="text-sm"><span class="font-medium capitalize">{{ $role }}:</span> {{ $count }}</p>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md border">
        <form method="GET" class="grid grid-cols-3 md:grid-cols-4 gap-3">
            <select name="role" class="form-input">
                <option value="">All Roles</option>
                @foreach(['admin','manager','caregiver','nurse','teacher'] as $role)
                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.staff') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ ucfirst($member->role) }}</td>
                        <td><span class="badge {{ $member->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-neutral-500">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $staff->links() }}</div>
    </div>
</div>
</x-layouts.app>
