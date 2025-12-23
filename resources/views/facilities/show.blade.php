
@section('title', 'Add New Facility')

<x-layouts.app>
    <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <div class="flex items-center space-x-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-neutral-900">{{ $facility->name }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="badge badge-{{ $facility->type === 'dormitory' ? 'info' : 'primary' }}">{{ ucfirst($facility->type) }}</span>

                    @if($facility->is_active)
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- facility Information -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Facility Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-neutral-600">Facility Name</label>
                    <p class="text-neutral-900">{{ $facility->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Facility Type</label>
                    <p class="text-neutral-900">{{ ($facility->type) }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-neutral-600">Description</label>
                    <p class="text-neutral-900">{{ $facility->description ?? 'Not specified' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Capacity</label>
                    <p class="text-neutral-900">{{ $facility->capacity ?? 'Not recorded' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Managed By</label>
                    <p class="text-neutral-900">{{ $facility->managedBy->name }}</p>
                </div>
            </div>
        </div>


    <!-- Facilities Stats -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <label class="text-sm font-medium text-neutral-600">Number  of Rooms :</label><p> {{ $facility->roomAllocations()->count() > 0 ? : 0 }} </p>
         <label class="text-sm font-medium text-neutral-600">Occupancy Rate :</label><p> {{ $totalOccupancy > 0 ? ($occupiedRooms / $totalRooms * 100) : 0 }}% </p>
         <label class="text-sm font-medium text-neutral-600">Pending Maintenance Request: </label><p>{{ $pendingMaintenance }} </p>
    </div>
        <!-- List of Children -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Rooms in {{ $facility->name }}</h3>
        @if($facility->roomAllocations->isEmpty())
            <p class="text-neutral-700">No rooms allocated in this facility.</p>
            <button onclick="window.location='{{ route('rooms.store') }}'" class="btn btn-primary mt-4">
                Allocate New Room</button>
        @else
            <ul class="space-y-2">
                @foreach($facility->roomAllocations as $room)
                    <li class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-md font-medium text-neutral-900">Room {{ $room->room_number }}</h4>
                                <p class="text-sm text-neutral-700">Capacity: {{ $room->capacity }}</p>
                            </div>
                            <div>
                                <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
      


    </div>
</div>
</x-layouts.app>