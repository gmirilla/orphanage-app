<x-layouts.app>
@php
    $pct              = $roomAllocation->bed_count > 0 ? round(($roomAllocation->occupied_beds / $roomAllocation->bed_count) * 100) : 0;
    $barColor         = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-[#324b45]');
    $avail            = max(0, $roomAllocation->bed_count - $roomAllocation->occupied_beds);
    $currentOccupants = $roomOccupants->filter(fn($o) => is_null($o->unassigned_date));
    $pastOccupants    = $roomOccupants->filter(fn($o) => !is_null($o->unassigned_date));
@endphp

<div class="space-y-6">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Hero header --}}
    <div class="bg-white rounded-xl shadow-sm border border-neutral-100 overflow-hidden">
        <div class="h-2 {{ $roomAllocation->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>
        <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-[#324b45]/10 flex items-center justify-center shrink-0 text-[#324b45] font-bold text-lg">
                {{ $roomAllocation->room_number }}
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-neutral-900">Room {{ $roomAllocation->room_number }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="text-xs text-neutral-500 flex items-center gap-1">
                        <i data-lucide="building-2" class="w-3 h-3"></i>
                        <a href="{{ route('facilities.show', $facility) }}" class="hover:underline">{{ $facility->name }}</a>
                    </span>
                    @if($roomAllocation->is_active)
                        <span class="text-xs font-medium text-green-700 bg-green-100 px-2.5 py-0.5 rounded-full">Active</span>
                    @else
                        <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2.5 py-0.5 rounded-full">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button" onclick="openAssignModal()" class="btn btn-primary">
                    <i data-lucide="user-plus" class="w-4 h-4 mr-1 inline-block"></i> Assign Child
                </button>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('rooms.edit', $roomAllocation) }}" class="btn btn-secondary">
                    <i data-lucide="pencil" class="w-4 h-4 mr-1 inline-block"></i> Edit
                </a>
                @endif
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    {{-- Metric cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                    <i data-lucide="bed" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Total Beds</p>
                    <p class="text-xl font-bold text-neutral-900">{{ $roomAllocation->bed_count }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="w-4 h-4 text-[#324b45]"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Occupied</p>
                    <p class="text-xl font-bold text-neutral-900">{{ $roomAllocation->occupied_beds }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                    <i data-lucide="bed" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Available</p>
                    <p class="text-xl font-bold text-green-700">{{ $avail }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <i data-lucide="percent" class="w-4 h-4 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Occupancy</p>
                    <p class="text-xl font-bold {{ $pct >= 90 ? 'text-red-600' : ($pct >= 70 ? 'text-amber-600' : 'text-[#324b45]') }}">{{ $pct }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Occupancy bar --}}
    @if($roomAllocation->bed_count > 0)
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-neutral-700">Bed Occupancy</h3>
            <span class="text-sm font-semibold {{ $pct >= 90 ? 'text-red-600' : ($pct >= 70 ? 'text-amber-600' : 'text-[#324b45]') }}">
                {{ $pct }}% — {{ $roomAllocation->occupied_beds }} of {{ $roomAllocation->bed_count }} beds
            </span>
        </div>
        <div class="w-full h-3 bg-neutral-100 rounded-full overflow-hidden">
            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-neutral-400 mt-1.5">
            <span>0</span>
            <span>{{ $roomAllocation->bed_count }} total beds</span>
        </div>
    </div>
    @endif

    {{-- Occupants + History --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Current Occupants --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h3 class="text-sm font-semibold text-neutral-900">
                    Current Occupants <span class="text-neutral-400 font-normal">({{ $currentOccupants->count() }})</span>
                </h3>
                <button type="button" onclick="openAssignModal()" class="btn btn-primary btn-sm">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> Assign
                </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-96 flex-1">
                @if($currentOccupants->isEmpty())
                    <div class="py-8 text-center text-neutral-400">
                        <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                        <p class="text-sm">No children currently assigned.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($currentOccupants as $occupant)
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-neutral-100 bg-neutral-50">
                            <div class="w-9 h-9 rounded-full bg-[#324b45]/10 flex items-center justify-center shrink-0 text-[#324b45] font-bold text-xs">
                                {{ strtoupper(substr($occupant->child->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900 truncate">{{ $occupant->child->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-neutral-500">Since {{ $occupant->assigned_date->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('children.show', $occupant->child_id) }}" class="btn btn-secondary btn-sm">View</a>
                                <form action="{{ route('rooms.unassignChild') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="childid" value="{{ $occupant->child_id }}">
                                    <input type="hidden" name="room_allocation_id" value="{{ $occupant->room_allocation_id }}">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Unassign {{ addslashes($occupant->child->name ?? 'this child') }} from this room?')">
                                        <i data-lucide="user-minus" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Assignment History --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col">
            <div class="px-5 py-4 border-b border-neutral-100">
                <h3 class="text-sm font-semibold text-neutral-900">
                    Past Occupants <span class="text-neutral-400 font-normal">({{ $pastOccupants->count() }})</span>
                </h3>
            </div>
            <div class="p-4 overflow-y-auto max-h-96 flex-1">
                @if($pastOccupants->isEmpty())
                    <div class="py-8 text-center text-neutral-400">
                        <i data-lucide="clock" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                        <p class="text-sm">No assignment history yet.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($pastOccupants->sortByDesc('unassigned_date') as $occupant)
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-neutral-100 bg-neutral-50 opacity-70">
                            <div class="w-9 h-9 rounded-full bg-neutral-200 flex items-center justify-center shrink-0 text-neutral-500 font-bold text-xs">
                                {{ strtoupper(substr($occupant->child->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-700 truncate">{{ $occupant->child->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-neutral-400">
                                    {{ $occupant->assigned_date->format('d M Y') }} → {{ $occupant->unassigned_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Assign Child Modal --}}
<div id="roomAssignModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">Assign Child to Room {{ $roomAllocation->room_number }}</h3>
            <button onclick="closeAssignModal()" class="text-zinc-400 hover:text-zinc-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('rooms.assignChild', $roomAllocation->id) }}">
            @csrf
            <div class="p-6 space-y-4">
                @if($avail <= 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        This room is full. No beds are currently available.
                    </div>
                @endif
                <div>
                    <label class="form-label">Select Child <span class="text-red-500">*</span></label>
                    <select name="childid" required class="form-input w-full" {{ $avail <= 0 ? 'disabled' : '' }}>
                        <option value="">— Choose a child —</option>
                        @foreach($children as $child)
                            @if(empty($child->currentRoomAssignment))
                                <option value="{{ $child->id }}">{{ $child->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="room_allocation_id" value="{{ $roomAllocation->id }}">
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeAssignModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary" {{ $avail <= 0 ? 'disabled' : '' }}>Assign to Room</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModal() {
    document.getElementById('roomAssignModal').classList.remove('hidden');
}
function closeAssignModal() {
    document.getElementById('roomAssignModal').classList.add('hidden');
}
document.getElementById('roomAssignModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssignModal();
});
</script>
</x-layouts.app>
