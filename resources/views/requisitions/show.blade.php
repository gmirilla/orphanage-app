<x-layouts.app>
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $requisition->title }}</h2>
            <p class="text-sm text-neutral-500 mt-1">
                {{ $types[$requisition->requisition_type] ?? $requisition->requisition_type }}
                &bull; Raised by <strong>{{ $requisition->submittedBy->name ?? '—' }}</strong>
                &bull; #{{ $requisition->id }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            @if($requisition->submitted_by === auth()->id() && $requisition->isEditable())
                <a href="{{ route('requisitions.edit', $requisition) }}" class="btn btn-secondary">Edit</a>
            @endif
            @if($requisition->submitted_by === auth()->id() && ($requisition->isDraft() || $requisition->isRejected()))
                <form method="POST" action="{{ route('requisitions.submit', $requisition) }}"
                      onsubmit="return confirm('Submit this requisition for approval?')">
                    @csrf
                    <button type="submit" class="btn btn-primary">Submit for Approval</button>
                </form>
            @endif
            @if($requisition->submitted_by === auth()->id() && $requisition->isDraft())
                <form method="POST" action="{{ route('requisitions.destroy', $requisition) }}"
                      onsubmit="return confirm('Delete this draft?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endif
            <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">Back</a>
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
        $bannerClass = match($requisition->status) {
            'approved'  => 'bg-green-50 border-green-200 text-green-800',
            'rejected'  => 'bg-red-50 border-red-200 text-red-800',
            'submitted' => 'bg-amber-50 border-amber-200 text-amber-800',
            default     => 'bg-neutral-50 border-neutral-200 text-neutral-600',
        };
        $statusLabel = match($requisition->status) {
            'submitted' => 'Pending Review',
            default     => ucfirst($requisition->status),
        };
    @endphp
    <div class="rounded-lg border px-5 py-4 {{ $bannerClass }}">
        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm font-medium">
            <span>Status: <strong>{{ $statusLabel }}</strong></span>
            @if($requisition->submitted_at)
                <span>&bull; Submitted {{ $requisition->submitted_at->format('d M Y, H:i') }}</span>
            @endif
            @if($requisition->reviewed_at)
                <span>&bull; Reviewed {{ $requisition->reviewed_at->format('d M Y, H:i') }} by
                    <strong>{{ $requisition->reviewedBy->name ?? '—' }}</strong></span>
            @endif
        </div>
        @if($requisition->review_notes)
            <p class="mt-2 text-sm"><strong>Reviewer note:</strong> {{ $requisition->review_notes }}</p>
        @endif
        @if($requisition->isApproved())
            <p class="mt-1 text-xs opacity-70">This requisition is approved and locked for editing.</p>
        @endif
    </div>

    {{-- Details grid --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-4">Requisition Details</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-neutral-500">Type</dt>
                <dd class="font-medium">{{ $types[$requisition->requisition_type] ?? $requisition->requisition_type }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Priority</dt>
                <dd>
                    @php
                        $pc = match($requisition->priority) {
                            'urgent' => 'text-red-700 bg-red-50',
                            'high'   => 'text-orange-700 bg-orange-50',
                            'medium' => 'text-amber-700 bg-amber-50',
                            default  => 'text-neutral-600 bg-neutral-100',
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $pc }}">
                        {{ ucfirst($requisition->priority) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-neutral-500">Amount Requested</dt>
                <dd class="font-medium">
                    {{ $requisition->amount ? $requisition->currency . ' ' . number_format($requisition->amount, 2) : '—' }}
                </dd>
            </div>
            <div>
                <dt class="text-neutral-500">Raised By</dt>
                <dd class="font-medium">{{ $requisition->submittedBy->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Date Raised</dt>
                <dd class="font-medium">{{ $requisition->created_at->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-neutral-500">Needed By</dt>
                <dd class="font-medium">{{ $requisition->needed_by_date ? $requisition->needed_by_date->format('d M Y') : '—' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Description & Justification --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100 space-y-4">
        <div>
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-2">Description</h3>
            <p class="text-sm text-neutral-800 whitespace-pre-wrap">{{ $requisition->description }}</p>
        </div>
        @if($requisition->justification)
        <div class="pt-3 border-t border-neutral-100">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-2">Justification</h3>
            <p class="text-sm text-neutral-800 whitespace-pre-wrap">{{ $requisition->justification }}</p>
        </div>
        @endif
    </div>

    {{-- Supporting Documents --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-4">
            Supporting Documents <span class="text-neutral-400 font-normal">({{ $requisition->documents->count() }})</span>
        </h3>
        @if($requisition->documents->count())
            <ul class="space-y-2">
                @foreach($requisition->documents as $doc)
                <li class="flex items-center gap-3 p-3 bg-neutral-50 rounded-lg border border-neutral-100">
                    <i data-lucide="file" class="w-5 h-5 text-neutral-400 shrink-0"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $doc->file_original_name }}</p>
                        <p class="text-xs text-neutral-500">{{ $doc->fileSizeForHumans() }} &bull; Uploaded by {{ $doc->uploadedBy->name ?? '—' }} &bull; {{ $doc->created_at->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('requisitions.documents.download', [$requisition, $doc]) }}"
                       class="btn btn-secondary btn-sm shrink-0">
                        <i data-lucide="download" class="w-4 h-4 mr-1 inline-block"></i> Download
                    </a>
                </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-neutral-500">No supporting documents attached.</p>
        @endif
    </div>

    {{-- Review Panel (Admin / Head of Operations only, when submitted) --}}
    @if($canReviewAll && $requisition->isSubmitted())
    <div id="review" class="bg-white rounded-lg p-6 shadow-sm border-2 border-amber-300 space-y-5">
        <h3 class="text-base font-semibold text-neutral-900">Review This Requisition</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Approve --}}
            <form method="POST" action="{{ route('requisitions.approve', $requisition) }}" class="space-y-3">
                @csrf
                <label class="form-label">Approval Note <span class="text-neutral-400">(optional)</span></label>
                <textarea name="review_notes" rows="4" class="form-input w-full"
                          placeholder="Any comments for the staff member…"></textarea>
                <button type="submit" class="btn btn-success w-full"
                        onclick="return confirm('Approve this requisition?')">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1 inline-block"></i> Approve Requisition
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="{{ route('requisitions.reject', $requisition) }}" class="space-y-3">
                @csrf
                <label class="form-label">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="review_notes" rows="4" class="form-input w-full" required
                          placeholder="Explain what needs to be revised or why the request cannot be approved…"></textarea>
                <button type="submit" class="btn btn-danger w-full"
                        onclick="return confirm('Reject this requisition?')">
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1 inline-block"></i> Reject Requisition
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Audit Trail --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-5">Audit Trail</h3>

        @if($requisition->auditLogs->count())
        <ol class="relative border-l border-neutral-200 space-y-6 ml-3">
            @foreach($requisition->auditLogs as $log)
            @php
                $color = $log->actionColor();
            @endphp
            <li class="ml-6">
                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full {{ $color }} ring-4 ring-white">
                    <i data-lucide="{{ $log->actionIcon() }}" class="w-3 h-3"></i>
                </span>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mb-1">
                    <span class="text-sm font-semibold text-neutral-900">{{ $log->actionLabel() }}</span>
                    @if($log->old_status && $log->new_status)
                        <span class="text-xs text-neutral-400">
                            {{ ucfirst($log->old_status) }} → {{ ucfirst($log->new_status) }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-neutral-500">
                    By <strong>{{ $log->performedBy->name ?? 'System' }}</strong>
                    &bull; {{ $log->created_at->format('d M Y, H:i') }}
                    @if($log->ip_address) &bull; IP: {{ $log->ip_address }} @endif
                </p>
                @if($log->notes)
                    <p class="mt-1 text-sm text-neutral-700 bg-neutral-50 rounded px-3 py-1.5 border border-neutral-100">
                        {{ $log->notes }}
                    </p>
                @endif
            </li>
            @endforeach
        </ol>
        @else
            <p class="text-sm text-neutral-500">No audit history yet.</p>
        @endif
    </div>

</div>
</x-layouts.app>
