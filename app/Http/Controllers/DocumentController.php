<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('uploadedBy');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('related_type')) {
            $query->where('related_type', $request->related_type);
        }

        $documents = $query->latest()->paginate(20);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|string|max:100',
            'file'         => 'required|file|max:10240',
            'description'  => 'nullable|string',
            'tags'         => 'nullable|array',
            'related_type' => 'nullable|string|max:100',
            'related_id'   => 'nullable|integer',
            'visibility'   => 'nullable|in:public,private,restricted',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('documents', 'private');

            Document::create([
                'title'        => $validated['title'],
                'type'         => $validated['type'],
                'file_path'    => $path,
                'file_name'    => $file->getClientOriginalName(),
                'file_size'    => $file->getSize(),
                'mime_type'    => $file->getMimeType(),
                'description'  => $validated['description'] ?? null,
                'tags'         => $validated['tags'] ?? null,
                'related_type' => $validated['related_type'] ?? null,
                'related_id'   => $validated['related_id'] ?? null,
                'visibility'   => $validated['visibility'] ?? 'private',
                'uploaded_by'  => Auth::id(),
            ]);

            return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading document: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to upload document. Please try again.');
        }
    }

    public function show(Document $document)
    {
        $document->load('uploadedBy');
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'tags'        => 'nullable|array',
            'visibility'  => 'required|in:public,private,restricted',
        ]);

        try {
            $document->update($validated);
            return redirect()->route('documents.show', $document)->with('success', 'Document updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating document: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update document. Please try again.');
        }
    }

    public function destroy(Document $document)
    {
        try {
            Storage::disk('private')->delete($document->file_path);
            $document->delete();
            return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting document: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete document. Please try again.');
        }
    }

    public function upload(Request $request)
    {
        return $this->store($request);
    }

    public function download(Document $document)
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }
}
