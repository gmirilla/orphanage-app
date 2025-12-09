@extends('layouts.app')

@section('title', 'Add New Staff Member')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Add New Staff Member</h2>
            <p class="text-sm text-neutral-600">Create a new staff member account and profile</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Staff
            </a>
        </div>
    </div>

    <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Personal Information -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                <i data-lucide="user" class="w-5 h-5 inline mr-2"></i>
                Personal Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="Enter full name">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           class="form-input @error('email') border-red-500 @enderror"
                           placeholder="Enter email address">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           class="form-input @error('phone') border-red-500 @enderror"
                           placeholder="Enter phone number">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="role" class="form-label">Role *</label>
                    <select id="role" 
                            name="role" 
                            required
                            class="form-input @error('role') border-red-500 @enderror">
                        <option value="">Select a role</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth') }}" 
                           class="form-input @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" 
                            name="gender" 
                            class="form-input @error('gender') border-red-500 @enderror">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" 
                              name="address" 
                              rows="2"
                              class="form-input @error('address') border-red-500 @enderror"
                              placeholder="Enter full address">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Account Security -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                <i data-lucide="lock" class="w-5 h-5 inline mr-2"></i>
                Account Security
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="Enter password (min 8 characters)">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           class="form-input @error('password_confirmation') border-red-500 @enderror"
                           placeholder="Confirm password">
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                <i data-lucide="briefcase" class="w-5 h-5 inline mr-2"></i>
                Employment Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employee_id" class="form-label">Employee ID *</label>
                    <input type="text" 
                           id="employee_id" 
                           name="employee_id" 
                           value="{{ old('employee_id') }}" 
                           required
                           class="form-input @error('employee_id') border-red-500 @enderror"
                           placeholder="Enter employee ID">
                    @error('employee_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="department" class="form-label">Department</label>
                    <select id="department" 
                            name="department" 
                            class="form-input @error('department') border-red-500 @enderror">
                        <option value="">Select department</option>
                        <option value="Administration" {{ old('department') === 'Administration' ? 'selected' : '' }}>Administration</option>
                        <option value="Care" {{ old('department') === 'Care' ? 'selected' : '' }}>Care</option>
                        <option value="Medical" {{ old('department') === 'Medical' ? 'selected' : '' }}>Medical</option>
                        <option value="Education" {{ old('department') === 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Operations" {{ old('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                    </select>
                    @error('department')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="position" class="form-label">Position</label>
                    <input type="text" 
                           id="position" 
                           name="position" 
                           value="{{ old('position') }}" 
                           class="form-input @error('position') border-red-500 @enderror"
                           placeholder="Enter position/title">
                    @error('position')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="date_hired" class="form-label">Date Hired *</label>
                    <input type="date" 
                           id="date_hired" 
                           name="date_hired" 
                           value="{{ old('date_hired') }}" 
                           required
                           class="form-input @error('date_hired') border-red-500 @enderror">
                    @error('date_hired')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="salary" class="form-label">Salary</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="salary" 
                               name="salary" 
                               value="{{ old('salary') }}" 
                               step="0.01"
                               min="0"
                               class="form-input pl-7 @error('salary') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('salary')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                <i data-lucide="phone" class="w-5 h-5 inline mr-2"></i>
                Emergency Contact
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                    <input type="text" 
                           id="emergency_contact_name" 
                           name="emergency_contact_name" 
                           value="{{ old('emergency_contact_name') }}" 
                           class="form-input @error('emergency_contact_name') border-red-500 @enderror"
                           placeholder="Enter emergency contact name">
                    @error('emergency_contact_name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                    <input type="text" 
                           id="emergency_contact_phone" 
                           name="emergency_contact_phone" 
                           value="{{ old('emergency_contact_phone') }}" 
                           class="form-input @error('emergency_contact_phone') border-red-500 @enderror"
                           placeholder="Enter emergency contact phone">
                    @error('emergency_contact_phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                <i data-lucide="file-text" class="w-5 h-5 inline mr-2"></i>
                Additional Information
            </h3>
            
            <div class="space-y-6">
                <div>
                    <label for="qualifications" class="form-label">Qualifications & Certifications</label>
                    <textarea id="qualifications" 
                              name="qualifications" 
                              rows="3"
                              class="form-input @error('qualifications') border-red-500 @enderror"
                              placeholder="Enter qualifications, certifications, and special training">{{ old('qualifications') }}</textarea>
                    @error('qualifications')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="form-input @error('notes') border-red-500 @enderror"
                              placeholder="Enter any additional notes about the staff member">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Create Staff Member
            </button>
        </div>
    </form>
</div>
@endsection