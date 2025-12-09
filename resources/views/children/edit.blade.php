@section('title', 'Add New Child')

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md border border-neutral-100">
            <div class="p-6 border-b border-neutral-200">
                <h1 class="text-2xl font-bold text-neutral-900">Edit Record for Child {{ $child->name }}</h1>
                <p class="text-sm text-neutral-600 mt-1">Edit the child's profile with all necessary information</p>
            </div>

            <form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="form-label">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ $child->name }}" required
                                class="form-control @error('name') border-red-500 @enderror w-full">
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="form-label">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                class="form-select @error('gender') border-red-500 @enderror w-full">
                                <option value="" disabled {{ !$child->gender ? 'selected' : '' }}>Select Gender
                                </option>
                                <option value="male" {{ old('gender', $child->gender) === 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female"
                                    {{ old('gender', $child->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"
                                    {{ old('gender', $child->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>

                            @error('gender')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="form-label">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', optional($child)->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('Y-m-d') : '') }}"
                                required max="{{ now()->format('Y-m-d') }}"
                                class="form-control w-full @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="error-message text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select id="blood_group" name="blood_group"
                                class="form-select @error('blood_group') border-red-500 @enderror w-full">
                                <option value="" disabled {{ !$child->blood_group ? 'selected' : '' }}>Select Blood Group</option>
                                <option value="A+" {{ old('blood_group', $child->blood_group) === 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group', $child->blood_group) === 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group', $child->blood_group) === 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group', $child->blood_group) === 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group', $child->blood_group) === 'AB+' ? 'selected' : '' }}>AB+
                                </option>
                                <option value="AB-" {{ old('blood_group', $child->blood_group) === 'AB-' ? 'selected' : '' }}>AB-
                                </option>
                                <option value="O+" {{ old('blood_group',$child->blood_group) === 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group', $child->blood_group) === 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('blood_group')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="height_cm" class="form-label">Height (cm)</label>
                            <input type="number" id="height_cm" name="height_cm" value="{{ old('height_cm', $child->height_cm) }}"
                                step="0.1" min="0" max="300"
                                class="form-control @error('height_cm') border-red-500 @enderror w-full">
                            @error('height_cm')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight_kg" class="form-label">Weight (kg)</label>
                            <input type="number" id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $child->weight_kg) }}"
                                step="0.1" min="0" max="200"
                                class="form-control @error('weight_kg') border-red-500 @enderror w-full">
                            @error('weight_kg')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                class="form-control @error('profile_photo') border-red-500 @enderror w-full">
                            <p class="text-sm text-neutral-600 mt-1">Upload a clear photo (JPEG, PNG, JPG, GIF - max
                                2MB)</p>
                            @error('profile_photo')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Admission Information -->
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Admission Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="admission_date" class="form-label">Admission Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="admission_date" name="admission_date"
                                value="{{ old('admission_date',optional($child)->admission_date ? \Carbon\Carbon::parse($child->admission_date)->format('Y-m-d') : '') }}" required
                                class="form-control @error('admission_date') border-red-500 @enderror w-full">
                            @error('admission_date')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admission_source" class="form-label">Admission Source <span
                                    class="text-red-500">*</span></label>
                            <select id="admission_source" name="admission_source" required
                                class="form-select @error('admission_source') border-red-500 @enderror w-full">
                                <option value="" disabled {{ !$child->admission_source ? 'selected' : '' }}>Select Source</option>
                                <option value="hospital"
                                    {{ old('admission_source', $child->admission_source) === 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="social_services"
                                    {{ old('admission_source', $child->admission_source) === 'social_services' ? 'selected' : '' }}>Social
                                    Services</option>
                                <option value="family_services"
                                    {{ old('admission_source', $child->admission_source) === 'family_services' ? 'selected' : '' }}>Family
                                    Services</option>
                                <option value="police" {{ old('admission_source', $child->admission_source) === 'police' ? 'selected' : '' }}>
                                    Police</option>
                                <option value="other" {{ old('admission_source', $child->admission_source) === 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                            @error('admission_source')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="guardianship_status" class="form-label">Guardianship Status</label>
                            <select id="guardianship_status" name="guardianship_status"
                                class="form-select @error('guardianship_status') border-red-500 @enderror w-full">
                                <option value="" disabled {{ !$child->guardianship_status ? 'selected' : '' }}>Select Status</option>
                                <option value="orphan"
                                    {{ old('guardianship_status', $child->guardianship_status) === 'orphan' ? 'selected' : '' }}>Orphan</option>
                                <option value="abandoned"
                                    {{ old('guardianship_status', $child->guardianship_status) === 'abandoned' ? 'selected' : '' }}>Abandoned
                                </option>
                                <option value="dependent"
                                    {{ old('guardianship_status', $child->guardianship_status) === 'dependent' ? 'selected' : '' }}>Dependent
                                </option>
                                <option value="temporary_care"
                                    {{ old('guardianship_status', $child->guardianship_status) === 'temporary_care' ? 'selected' : '' }}>Temporary
                                    Care</option>
                            </select>
                            @error('guardianship_status')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admitted_by" class="form-label">Admitted By <span
                                    class="text-red-500">*</span></label>
                            <select id="admitted_by" name="admitted_by" required
                                class="form-select @error('admitted_by') border-red-500 @enderror w-full">
                                <option value="" disabled {{ !$child->admitted_by ? 'selected' : '' }}>Select Staff Member</option>
                                @foreach ($staff as $member)
                                    <option value="{{ $member->id }}"
                                        {{ old('admitted_by', $child->admitted_by) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ ucfirst($member->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('admitted_by')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Background and Medical Information -->
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Background & Medical Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="background_summary" class="form-label">Background Summary <span
                                    class="text-red-500">*</span></label>
                            <textarea id="background_summary" name="background_summary" rows="4" required
                                placeholder="Provide a comprehensive background summary including family situation, circumstances leading to admission, and any relevant history..."
                                class="form-control @error('background_summary') border-red-500 @enderror w-full">{{ old('background_summary', $child->background_summary) }}</textarea>
                            @error('background_summary')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="special_needs" class="form-label">Special Needs</label>
                            <textarea id="special_needs" name="special_needs" rows="3"
                                placeholder="Any special medical, educational, or behavioral needs..."
                                class="form-control @error('special_needs') border-red-500 @enderror w-full">{{ old('special_needs', $child->special_needs) }}</textarea>
                            @error('special_needs')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="guardian_info" class="form-label">Guardian Information</label>
                            <textarea id="guardian_info" name="guardian_info" rows="3"
                                placeholder="Information about parents, guardians, or next of kin (if applicable)..."
                                class="form-control @error('guardian_info') border-red-500 @enderror w-full">{{ old('guardian_info', $child->guardian_info) }}</textarea>
                            @error('guardian_info')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-neutral-200">
                    <a href="{{ route('children.index') }}" class="btn btn-secondary">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

        <script>
            // Calculate and display age
            document.getElementById('date_of_birth').addEventListener('change', function() {
                const birthDate = new Date(this.value);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age >= 0) {
                    console.log(`Age: ${age} years`);
                }
            });

            // Validate admission date
            document.getElementById('admission_date').addEventListener('change', function() {
                const birthDate = new Date(document.getElementById('date_of_birth').value);
                const admissionDate = new Date(this.value);

                if (admissionDate <= birthDate) {
                    alert('Admission date must be after the date of birth.');
                    this.value = '';
                }
            });

            // Preview profile photo
            document.getElementById('profile_photo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can display a preview here if needed
                        console.log('Photo selected:', file.name, file.size);
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
</x-layouts.app>
