<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Children Report</h2>
            <p class="text-sm text-neutral-600">Active children and admission statistics</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to Reports</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
            <p class="text-sm text-neutral-600">Total Active</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-blue-400">{{ $stats['male'] }}</p>
            <p class="text-sm text-neutral-600">Male</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-pink-400">{{ $stats['female'] }}</p>
            <p class="text-sm text-neutral-600">Female</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['recent_30d'] }}</p>
            <p class="text-sm text-neutral-600">Last 30 Days</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-4 shadow-md border">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <select name="gender" class="form-input">
                <option value="">All Genders</option>
                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
            </select>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input" placeholder="From">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input" placeholder="To">
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.children') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Name</th><th>Gender</th><th>Age</th><th>Admitted</th><th>Source</th><th>Room</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($children as $child)
                    <tr>
                        <td><a href="{{ route('children.show', $child) }}" class="text-blue-600">{{ $child->name }}</a></td>
                        <td>{{ ucfirst($child->gender) }}</td>
                        <td>{{ $child->age }}</td>
                        <td>{{ $child->admission_date->format('M d, Y') }}</td>
                        <td>{{ $child->admission_source }}</td>
                        <td>{{ $child->currentRoomAssignment?->roomAllocation?->room_number ?? '—' }}</td>
                        <td><span class="badge {{ $child->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $child->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-neutral-500">No children found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $children->links() }}</div>
    </div>
</div>
</x-layouts.app>
