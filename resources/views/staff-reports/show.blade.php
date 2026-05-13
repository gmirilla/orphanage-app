<x-layouts.app>
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $report->title }}</h2>
            <p class="text-sm text-neutral-500 mt-1">
                {{ $reportTypes[$report->report_type] ?? $report->report_type }}
                &bull; {{ $classifications[$report->classification] ?? $report->classification }}
                &bull; Submitted by <strong>{{ $report->submittedBy->name ?? '—' }}</strong>
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            @if($report->submitted_by === auth()->id() && $report->isEditable())
                <a href="{{ route('staff-reports.edit', $report) }}" class="btn btn-secondary">Edit</a>
            @endif
            @if($report->submitted_by === auth()->id() && $report->isDraft())
                <form method="POST" action="{{ route('staff-reports.submit', $report) }}" onsubmit="return confirm('Submit this report for approval?')">
                    @csrf
                    <button type="submit" class="btn btn-primary">Submit for Approval</button>
                </form>
            @endif
            @if($report->submitted_by === auth()->id() && $report->isDraft())
                <form method="POST" action="{{ route('staff-reports.destroy', $report) }}" onsubmit="return confirm('Delete this draft?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endif
            <a href="{{ route('staff-reports.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Status banner --}}
    @php
        $bannerClass = match($report->status) {
            'approved'  => 'bg-green-50 border-green-200 text-green-800',
            'rejected'  => 'bg-red-50 border-red-200 text-red-800',
            'submitted' => 'bg-amber-50 border-amber-200 text-amber-800',
            default     => 'bg-neutral-50 border-neutral-200 text-neutral-600',
        };
        $statusLabel = match($report->status) {
            'submitted' => 'Pending Review',
            default     => ucfirst($report->status),
        };
    @endphp
    <div class="rounded-lg border px-5 py-4 {{ $bannerClass }}">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <span class="font-semibold">Status: {{ $statusLabel }}</span>
                @if($report->submitted_at)
                    &bull; Submitted {{ $report->submitted_at->format('d M Y, H:i') }}
                @endif
                @if($report->reviewed_at)
                    &bull; Reviewed {{ $report->reviewed_at->format('d M Y, H:i') }} by <strong>{{ $report->reviewedBy->name ?? '—' }}</strong>
                @endif
            </div>
        </div>
        @if($report->review_notes)
            <p class="mt-2 text-sm"><strong>Reviewer note:</strong> {{ $report->review_notes }}</p>
        @endif
        @if($report->isApproved())
            <p class="mt-1 text-xs opacity-75">This report is approved and locked for editing.</p>
        @endif
    </div>

    {{-- Meta --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-4">Report Details</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm">
            <div>
                <dt class="text-neutral-500">Type</dt>
                <dd class="font-medium">{{ $reportTypes[$report->report_type] ?? $report->report_type }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Classification</dt>
                <dd class="font-medium">{{ $classifications[$report->classification] ?? $report->classification }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Period</dt>
                <dd class="font-medium">
                    @if($report->period_start)
                        {{ $report->period_start->format('d M Y') }}
                        @if($report->period_end) – {{ $report->period_end->format('d M Y') }} @endif
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-neutral-500">Submitted By</dt>
                <dd class="font-medium">{{ $report->submittedBy->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Date Submitted</dt>
                <dd class="font-medium">{{ $report->submitted_at ? $report->submitted_at->format('d M Y') : '—' }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Created</dt>
                <dd class="font-medium">{{ $report->created_at->format('d M Y') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Content --}}
    @if($report->content)
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-4">Report Content</h3>
        <div class="prose prose-sm max-w-none text-neutral-800 whitespace-pre-wrap font-mono text-sm leading-relaxed bg-neutral-50 rounded p-4">{{ $report->content }}</div>
    </div>
    @endif

    {{-- File --}}
    @if($report->file_path)
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-3">Attached File</h3>
        <div class="flex items-center gap-4">
            <i data-lucide="file" class="w-8 h-8 text-neutral-400 shrink-0"></i>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-sm truncate">{{ $report->file_original_name }}</p>
            </div>
            <a href="{{ route('staff-reports.download', $report) }}" class="btn btn-secondary btn-sm">
                <i data-lucide="download" class="w-4 h-4 mr-1 inline-block"></i> Download
            </a>
        </div>
    </div>
    @endif

    {{-- Review panel (Head of Operations / Admin only, submitted reports) --}}
    @if($canReviewAll && $report->isSubmitted())
    <div id="review" class="bg-white rounded-lg p-6 shadow-sm border-2 border-amber-300 space-y-4">
        <h3 class="text-base font-semibold text-neutral-900">Review This Report</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Approve --}}
            <form method="POST" action="{{ route('staff-reports.approve', $report) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="form-label">Approval Note <span class="text-neutral-400">(optional)</span></label>
                    <textarea name="review_notes" rows="3" class="form-input w-full" placeholder="Any comments for the staff member…"></textarea>
                </div>
                <button type="submit" class="btn btn-success w-full" onclick="return confirm('Approve this report?')">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1 inline-block"></i> Approve Report
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="{{ route('staff-reports.reject', $report) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="form-label">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea name="review_notes" rows="3" class="form-input w-full" placeholder="Explain what needs to be revised…" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Reject this report?')">
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1 inline-block"></i> Reject Report
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
</x-layouts.app>
