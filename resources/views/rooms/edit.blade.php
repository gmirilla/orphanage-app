<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('rooms.view', $roomAllocation) }}" class="text-neutral-400 hover:text-neutral-600 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Edit Room {{ $roomAllocation->room_number }}</h2>
            <p class="text-sm text-neutral-500">{{ $facility->name }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Facility context --}}
    <div class="bg-[#324b45]/5 border border-[#324b45]/20 rounded-xl p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-[#324b45]/10 flex items-center justify-center shrink-0">
            <i data-lucide="building-2" class="w-4 h-4 text-[#324b45]"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-[#324b45]/60 font-medium uppercase tracking-wide">Facility</p>
            <p class="text-sm font-semibold text-[#324b45] truncate">{{ $facility->name }}</p>
        </div>
        <span class="shrink-0 text-xs font-medium px-2.5 py-0.5 rounded-full bg-[#324b45]/10 text-[#324b45]">
            {{ ucfirst($facility->type) }}
        </span>
    </div>

    <form method="POST" action="{{ route('rooms.update', $roomAllocation) }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Room Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Room Number / Name <span class="text-red-500">*</span></label>
                    <input type="text" name="room_number"
                           value="{{ old('room_number', $roomAllocation->room_number) }}"
                           class="form-input w-full" required>
                    @error('room_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Number of Beds <span class="text-red-500">*</span></label>
                    <input type="number" name="bed_count"
                           value="{{ old('bed_count', $roomAllocation->bed_count) }}"
                           class="form-input w-full" required min="1">
                    @error('bed_count')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="is_active" class="form-input w-full">
                    <option value="1" {{ old('is_active', $roomAllocation->is_active) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $roomAllocation->is_active) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            @if($roomAllocation->occupied_beds > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-700 flex items-start gap-2">
                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <span>{{ $roomAllocation->occupied_beds }} bed(s) currently occupied. The bed count cannot be reduced below this number.</span>
            </div>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('rooms.view', $roomAllocation) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Changes
            </button>
        </div>
    </form>
</div>
</x-layouts.app>
