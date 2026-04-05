<x-layouts.app>
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Room</h2>
        <p class="text-sm text-neutral-600">{{ $facility->name }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('rooms.update', $roomAllocation) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Room Number <span class="text-red-500">*</span></label>
                <input type="text" name="room_number" value="{{ old('room_number', $roomAllocation->room_number) }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="form-label">Bed Count <span class="text-red-500">*</span></label>
                <input type="number" name="bed_count" value="{{ old('bed_count', $roomAllocation->bed_count) }}" class="form-input w-full" required min="1">
                <p class="text-sm text-neutral-500 mt-1">Currently {{ $roomAllocation->occupied_beds }} bed(s) occupied.</p>
            </div>
            <div>
                <label class="form-label">Active</label>
                <select name="is_active" class="form-input w-full">
                    <option value="1" {{ old('is_active', $roomAllocation->is_active) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $roomAllocation->is_active) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('rooms.view', $roomAllocation) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Room</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
