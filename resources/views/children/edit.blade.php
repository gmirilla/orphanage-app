<x-layouts.app>
@php
    $currentAssignment = $child->currentRoomAssignment;
    $currentRoomId     = $currentAssignment?->room_allocation_id;
    $currentFacilityId = $currentAssignment?->roomAllocation?->facility_id;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('children.show', $child) }}" class="text-neutral-400 hover:text-neutral-600 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Edit — {{ $child->name }}</h2>
            <p class="text-sm text-neutral-500">Update this child's profile</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        {{-- Personal Information --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Personal Information</h3>

            <div>
                <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $child->name) }}" required class="form-input w-full">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="form-input w-full">
                        <option value="">Select gender…</option>
                        <option value="male"   {{ old('gender', $child->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $child->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender', $child->gender) === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth', $child->date_of_birth?->format('Y-m-d')) }}"
                           required max="{{ now()->format('Y-m-d') }}" class="form-input w-full">
                    @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-input w-full">
                        <option value="">Unknown</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $child->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                    @error('blood_group')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Profile Photo</label>
                    @if($child->profile_photo)
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ asset('storage/' . $child->profile_photo) }}" class="w-10 h-10 rounded-full object-cover" alt="Current photo">
                            <span class="text-xs text-neutral-500">Current photo — upload a new one to replace</span>
                        </div>
                    @endif
                    <input type="file" name="profile_photo" accept="image/*" class="form-input w-full">
                    <p class="text-xs text-neutral-500 mt-1">JPEG, PNG, JPG, GIF — max 2 MB</p>
                    @error('profile_photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Height (cm)</label>
                    <input type="number" name="height_cm" value="{{ old('height_cm', $child->height_cm) }}"
                           step="0.1" min="0" max="300" class="form-input w-full">
                    @error('height_cm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg', $child->weight_kg) }}"
                           step="0.1" min="0" max="200" class="form-input w-full">
                    @error('weight_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Admission Information --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Admission Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Admission Date <span class="text-red-500">*</span></label>
                    <input type="date" name="admission_date"
                           value="{{ old('admission_date', $child->admission_date?->format('Y-m-d')) }}"
                           required class="form-input w-full">
                    @error('admission_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Admission Source <span class="text-red-500">*</span></label>
                    <select name="admission_source" required class="form-input w-full">
                        <option value="">Select source…</option>
                        @foreach(['hospital' => 'Hospital', 'social_services' => 'Social Services', 'family_services' => 'Family Services', 'police' => 'Police', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('admission_source', $child->admission_source) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('admission_source')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Guardianship Status</label>
                    <select name="guardianship_status" class="form-input w-full">
                        <option value="">Unknown</option>
                        @foreach(['orphan' => 'Orphan', 'abandoned' => 'Abandoned', 'dependent' => 'Dependent', 'temporary_care' => 'Temporary Care'] as $val => $label)
                            <option value="{{ $val }}" {{ old('guardianship_status', $child->guardianship_status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('guardianship_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Admitted By <span class="text-red-500">*</span></label>
                    <select name="admitted_by" required class="form-input w-full">
                        <option value="">Select staff member…</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('admitted_by', $child->admitted_by) == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ ucfirst(str_replace('_', ' ', $member->role)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('admitted_by')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Background & Medical --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Background & Medical</h3>
            <div>
                <label class="form-label">Background Summary <span class="text-red-500">*</span></label>
                <textarea name="background_summary" rows="4" required class="form-input w-full">{{ old('background_summary', $child->background_summary) }}</textarea>
                @error('background_summary')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Special Needs</label>
                <textarea name="special_needs" rows="2" class="form-input w-full">{{ old('special_needs', $child->special_needs) }}</textarea>
                @error('special_needs')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Guardian Information</label>
                <textarea name="guardian_info" rows="2" class="form-input w-full">{{ old('guardian_info', $child->guardian_info) }}</textarea>
                @error('guardian_info')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Room Assignment --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Room Assignment</h3>
                @if($currentAssignment)
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Currently in <strong>{{ $currentAssignment->roomAllocation->room_number }}</strong>
                        at <strong>{{ $currentAssignment->roomAllocation->facility->name }}</strong>.
                        Select a different room to reassign, or leave blank to keep current.
                    </p>
                @else
                    <p class="text-xs text-neutral-500 mt-0.5">Not currently assigned to any room.</p>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Dormitory Facility</label>
                    <select id="facility_id" name="facility_id" class="form-input w-full"
                            onchange="loadRooms(this.value)"
                            data-current-room="{{ old('room_allocation_id', $currentRoomId) }}">
                        <option value="">— No change / Unassign —</option>
                        @foreach($dormitories as $dorm)
                            <option value="{{ $dorm->id }}"
                                {{ old('facility_id', $currentFacilityId) == $dorm->id ? 'selected' : '' }}>
                                {{ $dorm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Room</label>
                    <select id="room_allocation_id" name="room_allocation_id" class="form-input w-full" disabled>
                        <option value="">— Select a facility first —</option>
                    </select>
                    @error('room_allocation_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('children.show', $child) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<script>
async function loadRooms(facilityId) {
    const roomSelect = document.getElementById('room_allocation_id');
    roomSelect.innerHTML = '<option value="">Loading…</option>';
    roomSelect.disabled = true;
    if (!facilityId) {
        roomSelect.innerHTML = '<option value="">— Select a facility first —</option>';
        return;
    }
    const res = await fetch(`/facilities/${facilityId}/rooms-json`);
    const rooms = await res.json();
    if (rooms.length === 0) {
        roomSelect.innerHTML = '<option value="">No available rooms</option>';
        return;
    }
    roomSelect.innerHTML = '<option value="">— Select a room —</option>' +
        rooms.map(r => `<option value="${r.id}">${r.room_number} (${r.available_beds} bed${r.available_beds !== 1 ? 's' : ''} free)</option>`).join('');
    roomSelect.disabled = false;
}

document.addEventListener('DOMContentLoaded', function () {
    const facilitySelect = document.getElementById('facility_id');
    if (facilitySelect.value) {
        const targetRoom = facilitySelect.dataset.currentRoom;
        loadRooms(facilitySelect.value).then(() => {
            if (targetRoom) document.getElementById('room_allocation_id').value = targetRoom;
        });
    }
});
</script>
</x-layouts.app>
