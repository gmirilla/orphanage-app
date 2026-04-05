<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Volunteer</h2>
        <p class="text-sm text-neutral-600">{{ $volunteer->name }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('volunteers.update', $volunteer) }}" class="space-y-4">
            @csrf @method('PUT')

            <h3 class="font-semibold text-neutral-800 border-b pb-2">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $volunteer->name) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $volunteer->email) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">New Password <span class="text-neutral-400 text-xs">(leave blank to keep)</span></label>
                    <input type="password" name="password" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-input w-full">
                        <option value="">Select...</option>
                        <option value="male" {{ old('gender', $volunteer->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $volunteer->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $volunteer->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $volunteer->date_of_birth?->format('Y-m-d')) }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Active</label>
                    <select name="is_active" class="form-input w-full">
                        <option value="1" {{ old('is_active', $volunteer->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $volunteer->is_active) ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-input w-full">{{ old('address', $volunteer->address) }}</textarea>
                </div>
            </div>

            <h3 class="font-semibold text-neutral-800 border-b pb-2 pt-2">Volunteer Profile</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Volunteer ID <span class="text-red-500">*</span></label>
                    <input type="text" name="volunteer_id" value="{{ old('volunteer_id', $volunteer->volunteerProfile->volunteer_id ?? '') }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Availability</label>
                    <select name="availability" class="form-input w-full">
                        <option value="">Select...</option>
                        @foreach(['weekends','weekdays','evenings','flexible'] as $opt)
                        <option value="{{ $opt }}" {{ old('availability', $volunteer->volunteerProfile->availability ?? '') === $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Skills</label>
                    <textarea name="skills" rows="2" class="form-input w-full">{{ old('skills', $volunteer->volunteerProfile->skills ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Previous Experience</label>
                    <textarea name="previous_experience" rows="2" class="form-input w-full">{{ old('previous_experience', $volunteer->volunteerProfile->previous_experience ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Motivation</label>
                    <textarea name="motivation" rows="2" class="form-input w-full">{{ old('motivation', $volunteer->volunteerProfile->motivation ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2" class="form-input w-full">{{ old('notes', $volunteer->volunteerProfile->notes ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('volunteers.show', $volunteer) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Volunteer</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
