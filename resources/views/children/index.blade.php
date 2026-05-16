<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Children</h2>
            <p class="text-sm text-neutral-500">Manage child profiles, education, and development records</p>
        </div>
        @if(auth()->user()->canAccessChildren())
        <a href="{{ route('children.create') }}" class="btn btn-primary">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2 inline-block"></i> Add Child
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-5 h-5 text-[#324b45]"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Total Children</p>
                <p class="text-2xl font-bold text-neutral-900 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Boys</p>
                <p class="text-2xl font-bold text-blue-700 leading-tight">{{ $stats['boys'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-5 h-5 text-pink-500"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Girls</p>
                <p class="text-2xl font-bold text-pink-600 leading-tight">{{ $stats['girls'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                <i data-lucide="calendar-check" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Recent (3 mo.)</p>
                <p class="text-2xl font-bold text-green-700 leading-tight">{{ $stats['recent'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name…" class="form-input w-full">
            </div>
            <div class="w-36">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-input w-full">
                    <option value="">All</option>
                    <option value="male"   {{ request('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other"  {{ request('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="w-36">
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter" class="w-4 h-4 mr-1 inline-block"></i> Filter
                </button>
                <a href="{{ route('children.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Children Cards --}}
    @if($children->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($children as $child)
        @php
            $genderColor = match($child->gender) {
                'male'   => 'bg-blue-100 text-blue-700',
                'female' => 'bg-pink-100 text-pink-700',
                default  => 'bg-neutral-100 text-neutral-600',
            };
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-1.5 w-full {{ $child->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>
            <div class="p-5 flex-1 flex flex-col gap-3">

                {{-- Avatar + name --}}
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-[#324b45]/10 flex items-center justify-center text-[#324b45] font-bold text-lg shrink-0">
                        @if($child->profile_photo)
                            <img src="{{ asset('storage/' . $child->profile_photo) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($child->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-neutral-900 truncate leading-tight">{{ $child->name }}</h3>
                        <p class="text-xs text-neutral-500">{{ $child->age }} yrs · #{{ $child->id }}</p>
                    </div>
                    @if($child->is_active)
                        <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full shrink-0">Active</span>
                    @else
                        <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full shrink-0">Inactive</span>
                    @endif
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-1.5">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $genderColor }}">{{ ucfirst($child->gender) }}</span>
                    @if($child->guardianship_status)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-600">{{ ucfirst($child->guardianship_status) }}</span>
                    @endif
                </div>

                {{-- Details --}}
                <div class="space-y-1.5 text-xs text-neutral-500">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Admitted {{ $child->admission_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="door-open" class="w-3.5 h-3.5 shrink-0"></i>
                        @if($child->currentRoomAssignment)
                            <span class="truncate">
                                {{ $child->currentRoomAssignment->roomAllocation->facility->name }} —
                                Room {{ $child->currentRoomAssignment->roomAllocation->room_number }}
                            </span>
                        @else
                            <span class="text-neutral-400">No room assigned</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-4 flex gap-2">
                <a href="{{ route('children.show', $child) }}" class="btn btn-primary btn-sm flex-1 text-center">View</a>
                @if(auth()->user()->canAccessChildren())
                <a href="{{ route('children.edit', $child) }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </a>
                @endif
                @if(auth()->user()->isAdmin())
                <button onclick="confirmDelete({{ $child->id }}, '{{ addslashes($child->name) }}')" class="btn btn-danger btn-sm">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div>{{ $children->links() }}</div>

    @else
    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="users" class="w-8 h-8 text-neutral-400"></i>
        </div>
        <h3 class="text-base font-semibold text-neutral-900 mb-1">No children found</h3>
        <p class="text-sm text-neutral-500 mb-6">
            {{ request()->hasAny(['search','gender','status']) ? 'Try adjusting your filters.' : 'Get started by adding the first child.' }}
        </p>
        @if(auth()->user()->canAccessChildren())
        <a href="{{ route('children.create') }}" class="btn btn-primary">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2 inline-block"></i> Add Child
        </a>
        @endif
    </div>
    @endif

</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-zinc-900">Delete Child Record</h3>
        </div>
        <p class="text-sm text-zinc-600 mb-5">
            Are you sure you want to delete <span id="childName" class="font-semibold text-zinc-900"></span>?
            This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="deleteChild()" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>

<script>
let childToDelete = null;
function confirmDelete(id, name) {
    childToDelete = id;
    document.getElementById('childName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    childToDelete = null;
}
function deleteChild() {
    if (!childToDelete) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/children/${childToDelete}`;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
document.getElementById('deleteModal').addEventListener('click', e => {
    if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
});
</script>
</x-layouts.app>
