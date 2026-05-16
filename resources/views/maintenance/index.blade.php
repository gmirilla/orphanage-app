<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Maintenance Requests</h2>
            <p class="text-sm text-neutral-500">Track and manage facility maintenance work orders</p>
        </div>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> New Request
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
                <i data-lucide="wrench" class="w-5 h-5 text-[#324b45]"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Total Requests</p>
                <p class="text-2xl font-bold text-neutral-900 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Pending</p>
                <p class="text-2xl font-bold text-amber-700 leading-tight">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <i data-lucide="loader" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">In Progress</p>
                <p class="text-2xl font-bold text-blue-700 leading-tight">{{ $stats['in_progress'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-neutral-500 leading-tight">Urgent Open</p>
                <p class="text-2xl font-bold text-red-700 leading-tight">{{ $stats['urgent'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by title or facility…" class="form-input w-full">
            </div>
            <div class="w-36">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-input w-full">
                    <option value="">All</option>
                    <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="w-40">
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All</option>
                    <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled"   {{ request('status') === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter" class="w-4 h-4 mr-1 inline-block"></i> Filter
                </button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Request Cards --}}
    @if($requests->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($requests as $req)
        @php
            $priorityStripe = match($req->priority) {
                'urgent' => 'bg-red-500',
                'high'   => 'bg-orange-400',
                'medium' => 'bg-amber-400',
                default  => 'bg-blue-400',
            };
            $priorityBadge = match($req->priority) {
                'urgent' => 'bg-red-100 text-red-700',
                'high'   => 'bg-orange-100 text-orange-700',
                'medium' => 'bg-amber-100 text-amber-700',
                default  => 'bg-blue-100 text-blue-700',
            };
            $statusBadge = match($req->status) {
                'completed'   => 'bg-green-100 text-green-700',
                'in_progress' => 'bg-blue-100 text-blue-700',
                'cancelled'   => 'bg-neutral-100 text-neutral-500',
                default       => 'bg-amber-100 text-amber-700',
            };
            $statusLabel = match($req->status) {
                'in_progress' => 'In Progress',
                default       => ucfirst($req->status),
            };
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-neutral-100 flex flex-col overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-1.5 w-full {{ $priorityStripe }}"></div>
            <div class="p-5 flex-1 flex flex-col gap-3">

                {{-- Title + ID --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-neutral-900 leading-tight line-clamp-2">{{ $req->title }}</h3>
                        <p class="text-xs text-neutral-400 mt-0.5">#{{ $req->id }} · {{ $req->facility->name }}</p>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-1.5">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $priorityBadge }}">{{ ucfirst($req->priority) }}</span>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ $statusLabel }}</span>
                </div>

                {{-- Meta --}}
                <div class="space-y-1.5 text-xs text-neutral-500">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="user" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>By {{ $req->requestedBy->name }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>{{ $req->assignedTo?->name ?? 'Unassigned' }}</span>
                    </div>
                    @if($req->due_date)
                    <div class="flex items-center gap-1.5 {{ $req->due_date < now()->toDateString() && !in_array($req->status, ['completed','cancelled']) ? 'text-red-500 font-medium' : '' }}">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Due {{ \Carbon\Carbon::parse($req->due_date)->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-4 flex gap-2">
                <a href="{{ route('maintenance.view', $req) }}" class="btn btn-primary btn-sm flex-1 text-center">View</a>
                @if($isPrivileged)
                <a href="{{ route('maintenance.edit_request', $req) }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </a>
                <button onclick="confirmDelete({{ $req->id }}, '{{ addslashes($req->title) }}')" class="btn btn-danger btn-sm">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div>{{ $requests->links() }}</div>

    @else
    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="wrench" class="w-8 h-8 text-neutral-400"></i>
        </div>
        <h3 class="text-base font-semibold text-neutral-900 mb-1">No requests found</h3>
        <p class="text-sm text-neutral-500 mb-6">
            {{ request()->hasAny(['search','priority','status']) ? 'Try adjusting your filters.' : 'Submit the first maintenance request.' }}
        </p>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> New Request
        </a>
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
            <h3 class="text-base font-semibold text-zinc-900">Delete Request</h3>
        </div>
        <p class="text-sm text-zinc-600 mb-5">
            Are you sure you want to delete <span id="requestName" class="font-semibold text-zinc-900"></span>?
            This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="deleteRequest()" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>

<script>
let requestToDelete = null;
function confirmDelete(id, name) {
    requestToDelete = id;
    document.getElementById('requestName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    requestToDelete = null;
}
function deleteRequest() {
    if (!requestToDelete) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/maintenance/${requestToDelete}`;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
document.getElementById('deleteModal').addEventListener('click', e => {
    if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
});
</script>
</x-layouts.app>
