<x-layouts.app>
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Report</h2>
        <p class="text-sm text-neutral-600">
            @if($report->isRejected())
                This report was rejected. Revise and resubmit.
            @else
                Update your draft before submitting for approval.
            @endif
        </p>
    </div>

    @if($report->review_notes && $report->isRejected())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
        <strong>Rejection reason:</strong> {{ $report->review_notes }}
    </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('staff-reports.update', $report) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        {{-- Details --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Report Details</h3>

            <div>
                <label class="form-label">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $report->title) }}" class="form-input w-full" required>
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Report Type <span class="text-red-500">*</span></label>
                    <select name="report_type" class="form-input w-full" required>
                        <option value="">Select type…</option>
                        @foreach($reportTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('report_type', $report->report_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('report_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Classification <span class="text-red-500">*</span></label>
                    <select name="classification" class="form-input w-full" required>
                        <option value="">Select classification…</option>
                        @foreach($classifications as $key => $label)
                            <option value="{{ $key }}" {{ old('classification', $report->classification) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('classification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Period Start</label>
                    <input type="date" name="period_start" value="{{ old('period_start', $report->period_start?->format('Y-m-d')) }}" class="form-input w-full">
                    @error('period_start')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Period End</label>
                    <input type="date" name="period_end" value="{{ old('period_end', $report->period_end?->format('Y-m-d')) }}" class="form-input w-full">
                    @error('period_end')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Report Content</h3>

            <div>
                <label class="form-label">Written Content</label>
                <textarea name="content" rows="10" class="form-input w-full font-mono text-sm" placeholder="Type your report here…">{{ old('content', $report->content) }}</textarea>
                @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Replace File <span class="text-neutral-400">(PDF, Word, Excel, TXT — max 10 MB)</span></label>
                @if($report->file_original_name)
                    <p class="text-xs text-neutral-500 mb-1">
                        Current file: <strong>{{ $report->file_original_name }}</strong> — uploading a new file will replace it.
                    </p>
                @endif
                <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.xlsx,.xls" class="form-input w-full">
                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('staff-reports.show', $report) }}" class="btn btn-secondary">Cancel</a>
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
