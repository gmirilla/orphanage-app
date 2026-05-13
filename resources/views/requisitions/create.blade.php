<x-layouts.app>
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-neutral-900">New Requisition</h2>
        <p class="text-sm text-neutral-600">Complete the form below. Save as a draft or submit directly for approval.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('requisitions.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Details --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Requisition Details</h3>

            <div>
                <label class="form-label">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required
                       placeholder="e.g. Office Supplies for Q2">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Requisition Type <span class="text-red-500">*</span></label>
                    <select name="requisition_type" class="form-input w-full" required>
                        <option value="">Select type…</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('requisition_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('requisition_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="form-input w-full" required>
                        @foreach($priorities as $key => $label)
                            <option value="{{ $key }}" {{ old('priority', 'medium') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Amount Requested <span class="text-neutral-400">(if applicable)</span></label>
                    <div class="flex gap-2">
                        <select name="currency" class="form-input w-24 shrink-0">
                            @foreach(['USD','GBP','EUR','NGN','GHS','KES','ZAR'] as $cur)
                                <option value="{{ $cur }}" {{ old('currency', 'USD') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="0.01"
                               class="form-input w-full" placeholder="0.00">
                    </div>
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Date Needed By</label>
                    <input type="date" name="needed_by_date" value="{{ old('needed_by_date') }}" class="form-input w-full">
                    @error('needed_by_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Description & Justification</h3>
            <div>
                <label class="form-label">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="form-input w-full" required
                          placeholder="Describe what is being requested and the quantity/specifications…">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Justification / Business Case</label>
                <textarea name="justification" rows="4" class="form-input w-full"
                          placeholder="Explain why this requisition is necessary…">{{ old('justification') }}</textarea>
                @error('justification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-3">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Supporting Documents</h3>
            <p class="text-xs text-neutral-500">Attach quotes, invoices, or any supporting files. PDF, Word, Excel, and images accepted (max 10 MB each).</p>
            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg"
                   class="form-input w-full">
            @error('documents.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">Cancel</a>
            <div class="flex gap-3">
                <button type="submit" name="action" value="draft" class="btn btn-secondary">
                    <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Draft
                </button>
                <button type="submit" name="action" value="submit" class="btn btn-primary">
                    <i data-lucide="send" class="w-4 h-4 mr-1 inline-block"></i> Submit for Approval
                </button>
            </div>
        </div>
    </form>
</div>
</x-layouts.app>
