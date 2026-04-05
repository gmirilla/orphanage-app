<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Room Allocations</h2>
            <p class="text-sm text-neutral-600">All rooms across facilities</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="fa fa-plus mr-2"></i> Add Room
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        @if($rooms->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Facility</th>
                        <th>Beds</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td class="font-medium">{{ $room->room_number }}</td>
                        <td>{{ $room->facility->name ?? '—' }}</td>
                        <td>{{ $room->bed_count }}</td>
                        <td>{{ $room->occupied_beds }}</td>
                        <td>{{ $room->available_beds }}</td>
                        <td>
                            <span class="badge {{ $room->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $room->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('rooms.view', $room) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Deactivate this room?')">
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
        <div class="p-4">{{ $rooms->links() }}</div>
        @else
        <div class="p-12 text-center text-neutral-500">
            No rooms found. <a href="{{ route('rooms.create') }}" class="text-blue-600">Add the first room.</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
