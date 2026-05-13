<x-layouts.app>
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Requisition</h2>
        <p class="text-sm text-neutral-600">
            @if($requisition->isRejected())
                This requisition was rejected. Revise based on the feedback below and resubmit.
            @else
                Update your draft before submitting for approval.
            @endif
        </p>
    </div>

    @if($requisition->isRejected() && $requisition->review_notes)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
        <p class="font-semibold mb-1">Reviewer feedback:</p>
        <p>{{ $requisition->review_notes }}</p>
    </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('requisitions.update', $requisition) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        {{-- Details --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Requisition Details</h3>

            <div>
                <label class="form-label">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $requisition->title) }}"
                       class="form-input w-full" required>
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Requisition Type <span class="text-red-500">*</span></label>
                    <select name="requisition_type" class="form-input w-full" required>
                        <option value="">Select type…</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('requisition_type', $requisition->requisition_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('requisition_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="form-input w-full" required>
                        @foreach($priorities as $key => $label)
                            <option value="{{ $key }}" {{ old('priority', $requisition->priority) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Amount Requested</label>
                    <div class="flex gap-2">
                        <select name="currency" class="form-input w-24 shrink-0">
                            @foreach(['USD','GBP','EUR','NGN','GHS','KES','ZAR'] as $cur)
                                <option value="{{ $cur }}" {{ old('currency', $requisition->currency) === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="amount" value="{{ old('amount', $requisition->amount) }}"
                               min="0" step="0.01" class="form-input w-full" placeholder="0.00">
                    </div>
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Date Needed By</label>
                    <input type="date" name="needed_by_date"
                           value="{{ old('needed_by_date', $requisition->needed_by_date?->format('Y-m-d')) }}"
                           class="form-input w-full">
                    @error('needed_by_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Description & Justification</h3>
            <div>
                <label class="form-label">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="form-input w-full" required>{{ old('description', $requisition->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Justification</label>
                <textarea name="justification" rows="4" class="form-input w-full">{{ old('justification', $requisition->justification) }}</textarea>
                @error('justification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Existing documents --}}
        @if($requisition->documents->count())
        <div class="bg-white rounded-lg p-5 shadow-sm border border-neutral-100">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-3">
                Existing Documents <span class="text-neutral-400 font-normal">({{ $requisition->documents->count() }})</span>
            </h3>
            <ul class="space-y-2">
                @foreach($requisition->documents as $doc)
                <li class="flex items-center gap-3 p-3 bg-neutral-50 rounded-lg border border-neutral-100 text-sm">
                    <i data-lucide="file" class="w-4 h-4 text-neutral-400 shrink-0"></i>
                    <span class="flex-1 truncate">{{ $doc->file_original_name }}</span>
                    <span class="text-xs text-neutral-500">{{ $doc->fileSizeForHumans() }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Add more documents --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-3">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Add More Documents</h3>
            <p class="text-xs text-neutral-500">Newly uploaded files are added alongside any existing ones.</p>
            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg"
                   class="form-input w-full">
            @error('documents.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('requisitions.show', $requisition) }}" class="btn btn-secondary">Cancel</a>
            <div class="flex gap-3">
                <button type="submit" name="action" value="draft" class="btn btn-secondary">
                    <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Draft
                </button>
                <button type="submit" name="action" value="submit" class="btn btn-primary">
                    <i data-lucide="send" class="w-4 h-4 mr-1 inline-block"></i>
                    {{ $requisition->isRejected() ? 'Revise & Resubmit' : 'Submit for Approval' }}
                </button>
            </div>
        </div>
    </form>
</div>
</x-layouts.app>
