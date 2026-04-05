<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Facility</h2>
        <p class="text-sm text-neutral-600">{{ $facility->name }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('facilities.update', $facility) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $facility->name) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input w-full" required>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $facility->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $facility->capacity) }}" class="form-input w-full" min="0">
                </div>
                <div>
                    <label class="form-label">Managed By</label>
                    <select name="admin_id" class="form-input w-full">
                        <option value="">None</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('admin_id', $facility->managed_by) == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Active <span class="text-red-500">*</span></label>
                    <select name="is_active" class="form-input w-full" required>
                        <option value="1" {{ old('is_active', $facility->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $facility->is_active) ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input w-full">{{ old('description', $facility->description) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Facility</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
