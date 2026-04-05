<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Documents</h2>
            <p class="text-sm text-neutral-600">Manage uploaded files and records</p>
        </div>
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fa fa-upload mr-2"></i> Upload Document
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="form-input">
            <input type="text" name="type" value="{{ request('type') }}" placeholder="Type (e.g. birth_certificate)" class="form-input">
            <select name="related_type" class="form-input">
                <option value="">All Related Types</option>
                <option value="child" {{ request('related_type') === 'child' ? 'selected' : '' }}>Child</option>
                <option value="staff" {{ request('related_type') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="volunteer" {{ request('related_type') === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
            </select>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        @if($documents->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr><th>Title</th><th>Type</th><th>Related To</th><th>Visibility</th><th>Uploaded By</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr>
                        <td>
                            <p class="font-medium text-neutral-900">{{ $doc->title }}</p>
                            <p class="text-xs text-neutral-500">{{ $doc->file_name }}</p>
                        </td>
                        <td>{{ $doc->type }}</td>
                        <td>{{ $doc->related_type ? ucfirst($doc->related_type) . ' #' . $doc->related_id : '—' }}</td>
                        <td><span class="badge badge-secondary">{{ ucfirst($doc->visibility) }}</span></td>
                        <td>{{ $doc->uploadedBy->name ?? '—' }}</td>
                        <td>{{ $doc->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary btn-sm">Download</a>
                                <a href="{{ route('documents.edit', $doc) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $documents->links() }}</div>
        @else
        <div class="p-12 text-center text-neutral-500">
            No documents found. <a href="{{ route('documents.create') }}" class="text-blue-600">Upload the first document.</a>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
