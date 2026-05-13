<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">
                {{ $canReviewAll ? 'All Requisitions' : 'My Requisitions' }}
            </h2>
            <p class="text-sm text-neutral-600">
                {{ $canReviewAll ? 'Review and manage all requisition requests' : 'Track your requisition requests and their approval status' }}
            </p>
        </div>
        <a href="{{ route('requisitions.create') }}" class="btn btn-primary mt-4 sm:mt-0">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> New Requisition
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $base = $canReviewAll ? \App\Models\Requisition::query() : \App\Models\Requisition::ownedBy(auth()->id());
        @endphp
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Pending Review</p>
            <p class="text-2xl font-bold text-amber-600">{{ (clone $base)->pendingReview()->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Approved</p>
            <p class="text-2xl font-bold text-green-600">{{ (clone $base)->byStatus('approved')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Drafts</p>
            <p class="text-2xl font-bold text-neutral-500">{{ (clone $base)->byStatus('draft')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Total</p>
            <p class="text-2xl font-bold text-neutral-800">{{ $requisitions->total() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Title…" class="form-input w-full">
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="requisition_type" class="form-input w-full">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('requisition_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Priority</label>
                <select name="priority" class="form-input w-full">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $key => $label)
                        <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All Statuses</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Pending Review</option>
                    <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                    <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-neutral-100">
        <div class="p-5 border-b border-neutral-200 flex items-center justify-between">
            <h3 class="text-base font-semibold text-neutral-900">Requisitions</h3>
            @if($canReviewAll && $pendingCount > 0)
                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                    {{ $pendingCount }} awaiting review
                </span>
            @endif
        </div>

        @if($requisitions->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Priority</th>
                        @if($canReviewAll)<th>Raised By</th>@endif
                        <th>Amount</th>
                        <th>Needed By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisitions as $req)
                    @php
                        $priorityColor = match($req->priority) {
                            'urgent' => 'text-red-700 bg-red-50',
                            'high'   => 'text-orange-700 bg-orange-50',
                            'medium' => 'text-amber-700 bg-amber-50',
                            default  => 'text-neutral-600 bg-neutral-100',
                        };
                        $statusBadge = match($req->status) {
                            'approved'  => 'badge-success',
                            'rejected'  => 'badge-danger',
                            'submitted' => 'badge-warning',
                            default     => 'badge-secondary',
                        };
                        $statusLabel = match($req->status) {
                            'submitted' => 'Pending Review',
                            default     => ucfirst($req->status),
                        };
                    @endphp
                    <tr>
                        <td>
                            <p class="font-medium text-neutral-900">{{ $req->title }}</p>
                            <p class="text-xs text-neutral-400">#{{ $req->id }} &bull; {{ $req->documents->count() }} doc(s)</p>
                        </td>
                        <td class="text-sm">{{ $types[$req->requisition_type] ?? $req->requisition_type }}</td>
                        <td>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $priorityColor }}">
                                {{ ucfirst($req->priority) }}
                            </span>
                        </td>
                        @if($canReviewAll)
                        <td class="text-sm">{{ $req->submittedBy->name ?? '—' }}</td>
                        @endif
                        <td class="text-sm">
                            {{ $req->amount ? $req->currency . ' ' . number_format($req->amount, 2) : '—' }}
                        </td>
                        <td class="text-sm">
                            {{ $req->needed_by_date ? $req->needed_by_date->format('d M Y') : '—' }}
                        </td>
                        <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('requisitions.show', $req) }}" class="btn btn-primary btn-sm">View</a>
                                @if($req->submitted_by === auth()->id() && $req->isEditable())
                                    <a href="{{ route('requisitions.edit', $req) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @endif
                                @if($canReviewAll && $req->isSubmitted())
                                    <a href="{{ route('requisitions.show', $req) }}#review" class="btn btn-success btn-sm">Review</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $requisitions->links() }}</div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="clipboard-list" class="w-14 h-14 text-neutral-300 mx-auto mb-4"></i>
            <h3 class="text-base font-medium text-neutral-800 mb-1">No requisitions found</h3>
            <p class="text-sm text-neutral-500 mb-5">
                {{ $canReviewAll ? 'No requisitions have been submitted yet.' : 'You have not raised any requisitions yet.' }}
            </p>
            <a href="{{ route('requisitions.create') }}" class="btn btn-primary">Raise Requisition</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
