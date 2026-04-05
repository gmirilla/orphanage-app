<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Document</h2>
        <p class="text-sm text-neutral-600">{{ $document->file_name }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('documents.update', $document) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <input type="text" name="type" value="{{ old('type', $document->type) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Visibility <span class="text-red-500">*</span></label>
                    <select name="visibility" class="form-input w-full" required>
                        <option value="private" {{ old('visibility', $document->visibility) === 'private' ? 'selected' : '' }}>Private</option>
                        <option value="restricted" {{ old('visibility', $document->visibility) === 'restricted' ? 'selected' : '' }}>Restricted</option>
                        <option value="public" {{ old('visibility', $document->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input w-full">{{ old('description', $document->description) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
