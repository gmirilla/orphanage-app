<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div><h2 class="text-2xl font-bold text-neutral-900">Facilities Report</h2></div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            <p class="text-sm text-neutral-600">Total Facilities</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            <p class="text-sm text-neutral-600">Active</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border">
            @foreach($stats['by_type'] as $type => $count)
            <p class="text-sm"><span class="font-medium capitalize">{{ $type }}:</span> {{ $count }}</p>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md border">
        <form method="GET" class="grid grid-cols-3 gap-3">
            <select name="type" class="form-input">
                <option value="">All Types</option>
                @foreach(['dormitory','classroom','kitchen','clinic','office','recreation','storage'] as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.facilities') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Name</th><th>Type</th><th>Capacity</th><th>Rooms</th><th>Maintenance</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($facilities as $facility)
                    <tr>
                        <td><a href="{{ route('facilities.show', $facility) }}" class="text-blue-600">{{ $facility->name }}</a></td>
                        <td>{{ ucfirst($facility->type) }}</td>
                        <td>{{ $facility->capacity ?? '—' }}</td>
                        <td>{{ $facility->room_allocations_count }}</td>
                        <td>{{ $facility->maintenance_requests_count }}</td>
                        <td><span class="badge {{ $facility->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $facility->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-neutral-500">No facilities found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $facilities->links() }}</div>
    </div>
</div>
</x-layouts.app>
