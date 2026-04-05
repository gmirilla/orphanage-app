<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $facility->name }} — Rooms</h2>
            <p class="text-sm text-neutral-600">{{ ucfirst($facility->type) }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('rooms.create', ['facilityid' => $facility->id]) }}" class="btn btn-primary">
                <i class="fa fa-plus mr-2"></i> Add Room
            </a>
            <a href="{{ route('facilities.show', $facility) }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $rooms->total() }}</p>
            <p class="text-sm text-neutral-600">Total Rooms</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-neutral-700">{{ $rooms->sum('bed_count') }}</p>
            <p class="text-sm text-neutral-600">Total Beds</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $rooms->sum('occupied_beds') }}</p>
            <p class="text-sm text-neutral-600">Occupied</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $availableChildren->count() }}</p>
            <p class="text-sm text-neutral-600">Unassigned Children</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        @if($rooms->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr><th>Room</th><th>Beds</th><th>Occupied</th><th>Available</th><th>Occupancy</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td class="font-medium">{{ $room->room_number }}</td>
                        <td>{{ $room->bed_count }}</td>
                        <td>{{ $room->occupied_beds }}</td>
                        <td>{{ $room->available_beds }}</td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-neutral-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $room->occupancy_rate }}%"></div>
                                </div>
                                <span class="text-xs text-neutral-600">{{ $room->occupancy_rate }}%</span>
                            </div>
                        </td>
                        <td><span class="badge {{ $room->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $room->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('rooms.view', $room) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $rooms->links() }}</div>
        @else
        <div class="p-12 text-center text-neutral-500">
            No rooms yet. <a href="{{ route('rooms.create', ['facilityid' => $facility->id]) }}" class="text-blue-600">Add the first room.</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
