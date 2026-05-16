<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Facilities</h2>
            <p class="text-sm text-neutral-500">Manage buildings, rooms and occupancy across the site</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('facilities.create') }}" class="btn btn-primary mt-4 sm:mt-0">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Add Facility
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
                <i data-lucide="building-2" class="w-5 h-5 text-[#324b45]"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Total Facilities</p>
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
            <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center shrink-0">
                <i data-lucide="minus-circle" class="w-5 h-5 text-neutral-400"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Inactive</p>
                <p class="text-2xl font-bold text-neutral-500 leading-tight">{{ $stats['inactive'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                <i data-lucide="wrench" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Pending Maintenance</p>
                <p class="text-2xl font-bold text-amber-600 leading-tight">{{ $stats['pending_maintenance'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name or description…" class="form-input w-full">
            </div>
            <div class="w-44">
                <label class="form-label">Type</label>
                <select name="type" class="form-input w-full">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
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
                <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Facility Cards --}}
    @if($facilities->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($facilities as $facility)
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
            $iconKey   = $typeIcons[$facility->type]  ?? 'building-2';
            $colorKey  = $typeColors[$facility->type] ?? 'bg-neutral-100 text-neutral-600';
            $totalBeds = $facility->roomAllocations()->sum('bed_count');
            $occupied  = $facility->roomAllocations()->sum('occupied_beds');
            $available = max(0, $totalBeds - $occupied);
            $pct       = $totalBeds > 0 ? round(($occupied / $totalBeds) * 100) : 0;
            $barColor  = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-[#324b45]');
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col overflow-hidden hover:shadow-md transition-shadow">

            {{-- Card header band --}}
            <div class="h-1.5 w-full {{ $facility->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>

            <div class="p-5 flex-1 flex flex-col gap-4">

                {{-- Top row: icon + name + status --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg {{ $colorKey }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $iconKey }}" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-neutral-900 truncate leading-tight">{{ $facility->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $colorKey }}">
                                {{ $types[$facility->type] ?? ucfirst($facility->type) }}
                            </span>
                            @if($facility->is_active)
                                <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Active</span>
                            @else
                                <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($facility->description)
                <p class="text-xs text-neutral-500 line-clamp-2">{{ $facility->description }}</p>
                @endif

                {{-- Metrics row --}}
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-neutral-900">{{ $facility->room_allocations_count }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Rooms</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-neutral-900">{{ $occupied }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Occupied</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg py-2">
                        <p class="text-lg font-bold text-{{ $available > 0 ? 'green' : 'red' }}-600">{{ $available }}</p>
                        <p class="text-[10px] text-neutral-500 uppercase tracking-wide">Available</p>
                    </div>
                </div>

                {{-- Occupancy bar --}}
                @if($totalBeds > 0)
                <div>
                    <div class="flex justify-between text-[11px] text-neutral-500 mb-1">
                        <span>Bed Occupancy</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endif

                {{-- Manager --}}
                <div class="flex items-center gap-2 text-xs text-neutral-500">
                    <i data-lucide="user" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>{{ $facility->managedBy->name ?? 'Unassigned' }}</span>
                    @if($facility->maintenance_requests_count > 0)
                        <span class="ml-auto flex items-center gap-1 text-amber-600">
                            <i data-lucide="wrench" class="w-3 h-3"></i>
                            {{ $facility->maintenance_requests_count }} maintenance
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions footer --}}
            <div class="px-5 pb-4 flex gap-2">
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-primary btn-sm flex-1 text-center">
                    View Details
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </a>
                <button onclick="confirmDelete({{ $facility->id }}, '{{ addslashes($facility->name) }}')"
                        class="btn btn-danger btn-sm">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div>{{ $facilities->links() }}</div>

    @else
    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="building-2" class="w-8 h-8 text-neutral-400"></i>
        </div>
        <h3 class="text-base font-semibold text-neutral-900 mb-1">No facilities found</h3>
        <p class="text-sm text-neutral-500 mb-6">
            {{ request()->hasAny(['search','type','status']) ? 'Try adjusting your filters.' : 'Get started by adding the first facility.' }}
        </p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('facilities.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Add Facility
        </a>
        @endif
    </div>
    @endif
</div>

{{-- Delete modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-zinc-900">Deactivate Facility</h3>
        </div>
        <p class="text-sm text-zinc-600 mb-5">
            Are you sure you want to deactivate <span id="facilityName" class="font-semibold text-zinc-900"></span>?
            It will be marked inactive but not permanently deleted.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="deleteFacility()" class="btn btn-danger">Deactivate</button>
        </div>
    </div>
</div>

<script>
let facilityToDelete = null;

function confirmDelete(id, name) {
    facilityToDelete = id;
    document.getElementById('facilityName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    facilityToDelete = null;
}
function deleteFacility() {
    if (!facilityToDelete) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/facilities/${facilityToDelete}`;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
document.getElementById('deleteModal').addEventListener('click', e => {
    if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
});
</script>
</x-layouts.app>
