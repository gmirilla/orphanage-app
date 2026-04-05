<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Upload Document</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <input type="text" name="type" value="{{ old('type') }}" class="form-input w-full" required placeholder="e.g. birth_certificate, medical_record">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" class="form-input w-full" required>
                    <p class="text-xs text-neutral-500 mt-1">Max 10 MB</p>
                </div>
                <div>
                    <label class="form-label">Related To (Type)</label>
                    <select name="related_type" class="form-input w-full">
                        <option value="">None</option>
                        <option value="child" {{ old('related_type') === 'child' ? 'selected' : '' }}>Child</option>
                        <option value="staff" {{ old('related_type') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="volunteer" {{ old('related_type') === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                        <option value="donation" {{ old('related_type') === 'donation' ? 'selected' : '' }}>Donation</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Related ID</label>
                    <input type="number" name="related_id" value="{{ old('related_id') }}" class="form-input w-full" placeholder="Record ID">
                </div>
                <div>
                    <label class="form-label">Visibility</label>
                    <select name="visibility" class="form-input w-full">
                        <option value="private" {{ old('visibility', 'private') === 'private' ? 'selected' : '' }}>Private</option>
                        <option value="restricted" {{ old('visibility') === 'restricted' ? 'selected' : '' }}>Restricted</option>
                        <option value="public" {{ old('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input w-full">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
