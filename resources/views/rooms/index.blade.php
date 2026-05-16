<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Room Allocations</h2>
            <p class="text-sm text-neutral-500">All rooms across facilities</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Add Room
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
                <i data-lucide="door-open" class="w-5 h-5 text-[#324b45]"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Total Rooms</p>
                <p class="text-2xl font-bold text-neutral-900 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Active</p>
                <p class="text-2xl font-bold text-green-700 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <i data-lucide="bed" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Total Beds</p>
                <p class="text-2xl font-bold text-neutral-900 leading-tight">{{ $stats['total_beds'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Occupied Beds</p>
                <p class="text-2xl font-bold text-amber-600 leading-tight">{{ $stats['occupied'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="form-label">Facility</label>
                <select name="facility" class="form-input w-full">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $fac)
                        <option value="{{ $fac->id }}" {{ request('facility') == $fac->id ? 'selected' : '' }}>{{ $fac->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter" class="w-4 h-4 mr-1 inline-block"></i> Filter
                </button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Room Cards --}}
    @if($rooms->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($rooms as $room)
        @php
            $pct      = $room->bed_count > 0 ? round(($room->occupied_beds / $room->bed_count) * 100) : 0;
            $barColor = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-[#324b45]');
            $avail    = max(0, $room->bed_count - $room->occupied_beds);
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-1.5 w-full {{ $room->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>
            <div class="p-5 flex-1 flex flex-col gap-4">

                {{-- Top row --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0 text-[#324b45] font-bold text-sm">
                        {{ $room->room_number }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-neutral-900 truncate leading-tight">Room {{ $room->room_number }}</h3>
                        <div class="flex items-center gap-1 mt-1 text-xs text-neutral-500">
                            <i data-lucide="building-2" class="w-3 h-3 shrink-0"></i>
                            <span class="truncate">{{ $room->facility->name ?? '—' }}</span>
                        </div>
                    </div>
                    @if($room->is_active)
                        <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full shrink-0">Active</span>
                    @else
                        <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full shrink-0">Inactive</span>
                    @endif
                </div>

                {{-- Metrics --}}
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-neutral-900">{{ $room->bed_count }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Beds</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-neutral-900">{{ $room->occupied_beds }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Occupied</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-{{ $avail > 0 ? 'green' : 'red' }}-600">{{ $avail }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Available</p>
                    </div>
                </div>

                {{-- Occupancy bar --}}
                <div>
                    <div class="flex justify-between text-[11px] text-neutral-500 mb-1">
                        <span>Occupancy</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-4 flex gap-2">
                <a href="{{ route('rooms.view', $room) }}" class="btn btn-primary btn-sm flex-1 text-center">View</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </a>
                <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Deactivate this room?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i data-lucide="power" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div>{{ $rooms->links() }}</div>

    @else
    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="door-open" class="w-8 h-8 text-neutral-400"></i>
        </div>
        <h3 class="text-base font-semibold text-neutral-900 mb-1">No rooms found</h3>
        <p class="text-sm text-neutral-500 mb-6">
            {{ request()->hasAny(['facility','status']) ? 'Try adjusting your filters.' : 'Get started by adding the first room.' }}
        </p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Add Room
        </a>
        @endif
    </div>
    @endif

</div>
</x-layouts.app>
