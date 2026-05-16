<x-layouts.app>
@php
    $typeIcons = [
        'dormitory'  => 'home',
        'classroom'  => 'academic-cap',
        'kitchen'    => 'fire',
        'clinic'     => 'heart',
        'office'     => 'briefcase',
        'recreation' => 'sparkles',
        'storage'    => 'archive-box',
    ];
    $typeColors = [
        'dormitory'  => 'bg-blue-100 text-blue-700',
        'classroom'  => 'bg-purple-100 text-purple-700',
        'kitchen'    => 'bg-orange-100 text-orange-700',
        'clinic'     => 'bg-rose-100 text-rose-700',
        'office'     => 'bg-cyan-100 text-cyan-700',
        'recreation' => 'bg-yellow-100 text-yellow-700',
        'storage'    => 'bg-neutral-100 text-neutral-600',
    ];
    $icon      = $typeIcons[$facility->type]  ?? 'building-2';
    $color     = $typeColors[$facility->type] ?? 'bg-neutral-100 text-neutral-600';
    $totalBeds = $facility->roomAllocations()->sum('bed_count');
    $occupied  = $facility->roomAllocations()->sum('occupied_beds');
    $available = max(0, $totalBeds - $occupied);
    $pct       = $totalBeds > 0 ? round(($occupied / $totalBeds) * 100) : 0;
    $barColor  = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-[#324b45]');
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

    {{-- Hero header --}}
    <div class="bg-white rounded-xl shadow-sm border border-neutral-100 overflow-hidden">
        <div class="h-2 {{ $facility->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>
        <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-14 h-14 rounded-xl {{ $color }} flex items-center justify-center shrink-0">
                <i data-lucide="{{ $icon }}" class="w-7 h-7"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-neutral-900 truncate">{{ $facility->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $color }}">
                        {{ ucfirst($facility->type) }}
                    </span>
                    @if($facility->is_active)
                        <span class="text-xs font-medium text-green-700 bg-green-100 px-2.5 py-0.5 rounded-full">Active</span>
                    @else
                        <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2.5 py-0.5 rounded-full">Inactive</span>
                    @endif
                    @if($facility->managedBy)
                        <span class="text-xs text-neutral-500 flex items-center gap-1">
                            <i data-lucide="user" class="w-3 h-3"></i> {{ $facility->managedBy->name }}
                        </span>
                    @endif
                </div>
                @if($facility->description)
                    <p class="text-sm text-neutral-500 mt-2">{{ $facility->description }}</p>
                @endif
            </div>
            <div class="flex gap-2 shrink-0">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-secondary">
                    <i data-lucide="pencil" class="w-4 h-4 mr-1 inline-block"></i> Edit
                </a>
                @endif
                <button type="button" onclick="openMaintenanceModal()" class="btn btn-primary">
                    <i data-lucide="plus" class="w-4 h-4 mr-1 inline-block"></i> Maintenance
                </button>
                <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    {{-- Metric cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                    <i data-lucide="door-open" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Rooms</p>
                    <p class="text-xl font-bold text-neutral-900">{{ $totalRooms }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="w-4 h-4 text-[#324b45]"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Occupied Beds</p>
                    <p class="text-xl font-bold text-neutral-900">{{ $occupied }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                    <i data-lucide="bed" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Available Beds</p>
                    <p class="text-xl font-bold text-green-700">{{ $available }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <i data-lucide="wrench" class="w-4 h-4 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Pending Maintenance</p>
                    <p class="text-xl font-bold text-amber-600">{{ $pendingMaintenance }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Occupancy bar --}}
    @if($totalBeds > 0)
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-neutral-700">Bed Occupancy</h3>
            <span class="text-sm font-semibold {{ $pct >= 90 ? 'text-red-600' : ($pct >= 70 ? 'text-amber-600' : 'text-[#324b45]') }}">
                {{ $pct }}% — {{ $occupied }} of {{ $totalBeds }} beds
            </span>
        </div>
        <div class="w-full h-3 bg-neutral-100 rounded-full overflow-hidden">
            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-neutral-400 mt-1.5">
            <span>0</span>
            <span>{{ $totalBeds }} total beds</span>
        </div>
    </div>
    @endif

    {{-- Rooms + Maintenance side by side --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Rooms --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h3 class="text-sm font-semibold text-neutral-900">
                    Rooms <span class="text-neutral-400 font-normal">({{ $totalRooms }})</span>
                </h3>
                <a href="{{ route('rooms.create', ['facilityid' => $facility->id]) }}" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> Add Room
                </a>
            </div>
            <div class="p-4 overflow-y-auto max-h-96 flex-1">
                @if($facility->roomAllocations->isEmpty())
                    <div class="py-8 text-center text-neutral-400">
                        <i data-lucide="door-open" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                        <p class="text-sm">No rooms allocated yet.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($facility->roomAllocations as $room)
                        @php
                            $roomPct     = $room->bed_count > 0 ? round(($room->occupied_beds / $room->bed_count) * 100) : 0;
                            $roomBar     = $roomPct >= 90 ? 'bg-red-500' : ($roomPct >= 70 ? 'bg-amber-500' : 'bg-[#324b45]');
                            $roomAvail   = max(0, $room->bed_count - $room->occupied_beds);
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-neutral-100 bg-neutral-50 hover:bg-white transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0 text-[#324b45] font-bold text-xs">
                                {{ $room->room_number }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-sm font-medium text-neutral-900">Room {{ $room->room_number }}</p>
                                    <p class="text-xs text-neutral-500">{{ $room->occupied_beds }}/{{ $room->bed_count }} beds</p>
                                </div>
                                <div class="w-full h-1.5 bg-neutral-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ $roomBar }} rounded-full" style="width: {{ $roomPct }}%"></div>
                                </div>
                            </div>
                            <a href="{{ route('rooms.view', $room) }}" class="btn btn-secondary btn-sm shrink-0">View</a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Maintenance Requests --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h3 class="text-sm font-semibold text-neutral-900">
                    Maintenance <span class="text-neutral-400 font-normal">({{ $allmaintenanceRequests }})</span>
                </h3>
                <button type="button" onclick="openMaintenanceModal()" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> New
                </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-96 flex-1">
                @if($facility->maintenanceRequests->isEmpty())
                    <div class="py-8 text-center text-neutral-400">
                        <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                        <p class="text-sm">No maintenance requests on record.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($facility->maintenanceRequests->sortByDesc('created_at')->take(15) as $mr)
                        @php
                            $pColor = match($mr->priority) {
                                'urgent' => 'text-red-700 bg-red-50 border-red-200',
                                'high'   => 'text-orange-700 bg-orange-50 border-orange-200',
                                'medium' => 'text-amber-700 bg-amber-50 border-amber-200',
                                default  => 'text-neutral-600 bg-neutral-50 border-neutral-200',
                            };
                            $sBadge = match($mr->status) {
                                'completed'   => 'badge-success',
                                'in_progress' => 'badge-warning',
                                'cancelled'   => 'badge-secondary',
                                default       => 'badge-danger',
                            };
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-lg border {{ $pColor }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $mr->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-semibold uppercase tracking-wide">{{ $mr->priority }}</span>
                                    <span class="text-[10px] text-neutral-400">{{ $mr->requested_date ? $mr->requested_date->format('d M Y') : '' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="badge {{ $sBadge }}">{{ ucfirst(str_replace('_', ' ', $mr->status)) }}</span>
                                <a href="{{ route('maintenance.view', $mr) }}" class="text-neutral-400 hover:text-neutral-700">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Maintenance Request Modal --}}
<div id="maintenanceRequestModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">New Maintenance Request</h3>
            <button onclick="closeMaintenanceModal()" class="text-zinc-400 hover:text-zinc-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('maintenance.new') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="form-input w-full" placeholder="Brief description of the issue">
                </div>
                <div>
                    <label class="form-label">Details</label>
                    <textarea name="description" rows="3" class="form-input w-full" placeholder="Additional context…"></textarea>
                </div>
                <div>
                    <label class="form-label">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="form-input w-full">
                        <option value="">Select priority…</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeMaintenanceModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
function openMaintenanceModal() {
    document.getElementById('maintenanceRequestModal').classList.remove('hidden');
}
function closeMaintenanceModal() {
    document.getElementById('maintenanceRequestModal').classList.add('hidden');
}
document.getElementById('maintenanceRequestModal').addEventListener('click', function(e) {
    if (e.target === this) closeMaintenanceModal();
});
</script>
</x-layouts.app>
