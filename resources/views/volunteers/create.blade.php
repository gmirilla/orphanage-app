<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Register Volunteer</h2>
        <p class="text-sm text-neutral-600">Add a new volunteer to the system</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('volunteers.store') }}" class="space-y-4">
            @csrf

            <h3 class="font-semibold text-neutral-800 border-b pb-2">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-input w-full">
                        <option value="">Select...</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-input w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-input w-full">{{ old('address') }}</textarea>
                </div>
            </div>

            <h3 class="font-semibold text-neutral-800 border-b pb-2 pt-2">Volunteer Profile</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Volunteer ID <span class="text-red-500">*</span></label>
                    <input type="text" name="volunteer_id" value="{{ old('volunteer_id') }}" class="form-input w-full" required placeholder="e.g. VOL-001">
                </div>
                <div>
                    <label class="form-label">Availability</label>
                    <select name="availability" class="form-input w-full">
                        <option value="">Select...</option>
                        <option value="weekends" {{ old('availability') === 'weekends' ? 'selected' : '' }}>Weekends</option>
                        <option value="weekdays" {{ old('availability') === 'weekdays' ? 'selected' : '' }}>Weekdays</option>
                        <option value="evenings" {{ old('availability') === 'evenings' ? 'selected' : '' }}>Evenings</option>
                        <option value="flexible" {{ old('availability') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Skills</label>
                    <textarea name="skills" rows="2" class="form-input w-full" placeholder="e.g. teaching, nursing, cooking...">{{ old('skills') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Previous Experience</label>
                    <textarea name="previous_experience" rows="2" class="form-input w-full">{{ old('previous_experience') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Motivation</label>
                    <textarea name="motivation" rows="2" class="form-input w-full" placeholder="Why do you want to volunteer?">{{ old('motivation') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2" class="form-input w-full">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Register Volunteer</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
