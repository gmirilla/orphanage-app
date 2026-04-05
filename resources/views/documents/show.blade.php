<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $document->title }}</h2>
            <p class="text-sm text-neutral-600">{{ $document->type }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">Download</a>
            <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-neutral-500">File Name</dt><dd>{{ $document->file_name }}</dd></div>
            <div><dt class="text-neutral-500">MIME Type</dt><dd>{{ $document->mime_type }}</dd></div>
            <div><dt class="text-neutral-500">File Size</dt><dd>{{ number_format($document->file_size / 1024, 1) }} KB</dd></div>
            <div><dt class="text-neutral-500">Visibility</dt><dd><span class="badge badge-secondary">{{ ucfirst($document->visibility) }}</span></dd></div>
            <div><dt class="text-neutral-500">Related To</dt><dd>{{ $document->related_type ? ucfirst($document->related_type) . ' #' . $document->related_id : '—' }}</dd></div>
            <div><dt class="text-neutral-500">Uploaded By</dt><dd>{{ $document->uploadedBy->name ?? '—' }}</dd></div>
            <div><dt class="text-neutral-500">Uploaded On</dt><dd>{{ $document->created_at->format('M d, Y H:i') }}</dd></div>
            @if($document->tags)
            <div class="col-span-2"><dt class="text-neutral-500">Tags</dt><dd>{{ implode(', ', $document->tags) }}</dd></div>
            @endif
            @if($document->description)
            <div class="col-span-2"><dt class="text-neutral-500">Description</dt><dd>{{ $document->description }}</dd></div>
            @endif
        </dl>
    </div>
</div>
</x-layouts.app>
