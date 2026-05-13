<x-layouts.app>
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">
                {{ $canReviewAll ? 'All Staff Reports' : 'My Reports' }}
            </h2>
            <p class="text-sm text-neutral-600">
                {{ $canReviewAll ? 'Review and manage all submitted reports' : 'Track your submitted reports and their approval status' }}
            </p>
        </div>
        <a href="{{ route('staff-reports.create') }}" class="btn btn-primary mt-4 sm:mt-0">
            <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> New Report
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
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Pending Review</p>
            <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Total Reports</p>
            <p class="text-2xl font-bold text-neutral-800">{{ $reports->total() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Approved</p>
            @php
                $approvedCount = $canReviewAll
                    ? \App\Models\Report::byStatus('approved')->count()
                    : \App\Models\Report::byStatus('approved')->ownedBy(auth()->id())->count();
            @endphp
            <p class="text-2xl font-bold text-green-600">{{ $approvedCount }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Drafts</p>
            @php
                $draftCount = $canReviewAll
                    ? \App\Models\Report::byStatus('draft')->count()
                    : \App\Models\Report::byStatus('draft')->ownedBy(auth()->id())->count();
            @endphp
            <p class="text-2xl font-bold text-neutral-500">{{ $draftCount }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Report title..." class="form-input w-full">
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="report_type" class="form-input w-full">
                    <option value="">All Types</option>
                    @foreach($reportTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('report_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Classification</label>
                <select name="classification" class="form-input w-full">
                    <option value="">All Classifications</option>
                    @foreach($classifications as $key => $label)
                        <option value="{{ $key }}" {{ request('classification') === $key ? 'selected' : '' }}>{{ $label }}</option>
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
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter" class="w-4 h-4 mr-1 inline-block"></i> Filter
                </button>
                <a href="{{ route('staff-reports.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-neutral-100">
        <div class="p-5 border-b border-neutral-200">
            <h3 class="text-base font-semibold text-neutral-900">Reports</h3>
        </div>

        @if($reports->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Classification</th>
                        @if($canReviewAll)<th>Submitted By</th>@endif
                        <th>Period</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>
                            <p class="font-medium text-neutral-900">{{ $report->title }}</p>
                            <p class="text-xs text-neutral-400">#{{ $report->id }}</p>
                        </td>
                        <td>{{ $reportTypes[$report->report_type] ?? $report->report_type }}</td>
                        <td>{{ $classifications[$report->classification] ?? $report->classification }}</td>
                        @if($canReviewAll)
                        <td>{{ $report->submittedBy->name ?? '—' }}</td>
                        @endif
                        <td class="text-sm">
                            @if($report->period_start)
                                {{ $report->period_start->format('d M Y') }}
                                @if($report->period_end) – {{ $report->period_end->format('d M Y') }} @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-sm">{{ $report->submitted_at ? $report->submitted_at->format('d M Y') : '—' }}</td>
                        <td>
                            @php
                                $badgeClass = match($report->status) {
                                    'approved'  => 'badge-success',
                                    'rejected'  => 'badge-danger',
                                    'submitted' => 'badge-warning',
                                    default     => 'badge-secondary',
                                };
                                $label = match($report->status) {
                                    'submitted' => 'Pending Review',
                                    default     => ucfirst($report->status),
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('staff-reports.show', $report) }}" class="btn btn-primary btn-sm">View</a>
                                @if($report->submitted_by === auth()->id() && $report->isEditable())
                                    <a href="{{ route('staff-reports.edit', $report) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @endif
                                @if($canReviewAll && $report->isSubmitted())
                                    <a href="{{ route('staff-reports.show', $report) }}#review" class="btn btn-success btn-sm">Review</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $reports->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="file-text" class="w-14 h-14 text-neutral-300 mx-auto mb-4"></i>
            <h3 class="text-base font-medium text-neutral-800 mb-1">No reports found</h3>
            <p class="text-sm text-neutral-500 mb-5">
                {{ $canReviewAll ? 'No reports have been submitted yet.' : 'You have not submitted any reports yet.' }}
            </p>
            <a href="{{ route('staff-reports.create') }}" class="btn btn-primary">Create First Report</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
