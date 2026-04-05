<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">New Maintenance Request</h2>
        <p class="text-sm text-neutral-600">Submit a new maintenance request</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('maintenance.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Facility <span class="text-red-500">*</span></label>
                    <select name="facility_id" class="form-input w-full" required>
                        <option value="">Select facility...</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }} ({{ ucfirst($facility->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required placeholder="Brief summary of the issue">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" class="form-input w-full" required placeholder="Detailed description of the maintenance issue...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="form-input w-full" required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-input w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Estimated Cost</label>
                    <input type="number" name="estimated_cost" value="{{ old('estimated_cost') }}" step="0.01" min="0" class="form-input w-full" placeholder="0.00">
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
