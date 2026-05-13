<x-layouts.app>
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-neutral-900">New Report</h2>
        <p class="text-sm text-neutral-600">Write or upload your report, then save as a draft or submit for approval.</p>
    </div>

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

    <form method="POST" action="{{ route('staff-reports.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Title --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Report Details</h3>

            <div>
                <label class="form-label">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required placeholder="e.g. Weekly Child Welfare Report — Week 18">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Report Type <span class="text-red-500">*</span></label>
                    <select name="report_type" class="form-input w-full" required>
                        <option value="">Select type…</option>
                        @foreach($reportTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('report_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('report_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Classification <span class="text-red-500">*</span></label>
                    <select name="classification" class="form-input w-full" required>
                        <option value="">Select classification…</option>
                        @foreach($classifications as $key => $label)
                            <option value="{{ $key }}" {{ old('classification') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('classification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Period Start</label>
                    <input type="date" name="period_start" value="{{ old('period_start') }}" class="form-input w-full">
                    @error('period_start')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Period End</label>
                    <input type="date" name="period_end" value="{{ old('period_end') }}" class="form-input w-full">
                    @error('period_end')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Report Content</h3>
            <p class="text-xs text-neutral-500">Provide typed content, upload a file, or both.</p>

            <div>
                <label class="form-label">Written Content</label>
                <textarea name="content" rows="10" class="form-input w-full font-mono text-sm" placeholder="Type your report here…">{{ old('content') }}</textarea>
                @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Upload File <span class="text-neutral-400">(PDF, Word, Excel, TXT — max 10 MB)</span></label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.xlsx,.xls" class="form-input w-full">
                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('staff-reports.index') }}" class="btn btn-secondary">Cancel</a>
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
