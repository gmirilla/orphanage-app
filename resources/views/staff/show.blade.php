@extends('layouts.app')

@section('title', 'Staff Profile - ' . $staff->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-primary-500 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($staff->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-neutral-900">{{ $staff->name }}</h2>
                <p class="text-sm text-neutral-600">
                    {{ $staff->staffProfile?->position ?? 'No Position Set' }} • 
                    {{ ucfirst($staff->role) }}
                </p>
                <div class="flex items-center mt-1 space-x-2">
                    @if($staff->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-2">
            <a href="{{ route('staff.shifts', $staff) }}" class="btn btn-secondary">
                <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                View Shifts
            </a>
            <a href="{{ route('staff.edit', $staff) }}" class="btn btn-primary">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Total Shifts</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $staff->shiftSchedules->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Completed Shifts</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $staff->shiftSchedules->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Upcoming Shifts</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $staff->shiftSchedules->where('shift_date', '>=', now())->where('status', 'scheduled')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">This Month</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $staff->shiftSchedules->where('shift_date', '>=', now()->startOfMonth())->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Shifts -->
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Recent Shift Schedule</h3>
                </div>
                <div class="p-6">
                    @if($staff->shiftSchedules->count() > 0)
                        <div class="space-y-4">
                            @foreach($staff->shiftSchedules->take(10) as $shift)
                            <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="calendar" class="w-5 h-5 text-primary-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-neutral-900">
                                            {{ $shift->shift_date->format('F d, Y') }}
                                        </p>
                                        <p class="text-sm text-neutral-600">
                                            {{ date('g:i A', strtotime($shift->start_time)) }} - 
                                            {{ date('g:i A', strtotime($shift->end_time)) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $shift->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($shift->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                           ($shift->status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                           'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $shift->status)) }}
                                    </span>
                                    @if($shift->shift_type)
                                        <p class="text-xs text-neutral-500 mt-1">
                                            {{ ucfirst(str_replace('_', ' ', $shift->shift_type)) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if($staff->shiftSchedules->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('staff.shifts', $staff) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All Shifts ({{ $staff->shiftSchedules->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="calendar" class="w-12 h-12 text-neutral-400 mx-auto mb-3"></i>
                            <h4 class="text-sm font-medium text-neutral-900 mb-1">No shifts scheduled</h4>
                            <p class="text-sm text-neutral-600">This staff member doesn't have any shift schedules yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Schedule New Shift -->
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Schedule New Shift</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.schedule-shift', $staff) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shift_date" class="form-label">Shift Date *</label>
                                <input type="date" 
                                       id="shift_date" 
                                       name="shift_date" 
                                       value="{{ old('shift_date') }}" 
                                       required
                                       min="{{ date('Y-m-d') }}"
                                       class="form-input @error('shift_date') border-red-500 @enderror">
                                @error('shift_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="shift_type" class="form-label">Shift Type *</label>
                                <select id="shift_type" 
                                        name="shift_type" 
                                        required
                                        class="form-input @error('shift_type') border-red-500 @enderror">
                                    <option value="">Select shift type</option>
                                    <option value="morning">Morning (6AM - 2PM)</option>
                                    <option value="afternoon">Afternoon (2PM - 10PM)</option>
                                    <option value="night">Night (10PM - 6AM)</option>
                                    <option value="full_day">Full Day (8AM - 8PM)</option>
                                    <option value="part_time">Part Time</option>
                                </select>
                                @error('shift_type')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="time" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="{{ old('start_time') }}" 
                                       required
                                       class="form-input @error('start_time') border-red-500 @enderror">
                                @error('start_time')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_time" class="form-label">End Time *</label>
                                <input type="time" 
                                       id="end_time" 
                                       name="end_time" 
                                       value="{{ old('end_time') }}" 
                                       required
                                       class="form-input @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="department" class="form-label">Department</label>
                                <input type="text" 
                                       id="department" 
                                       name="department" 
                                       value="{{ old('department', $staff->staffProfile?->department) }}" 
                                       class="form-input @error('department') border-red-500 @enderror"
                                       placeholder="Enter department">
                                @error('department')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="2"
                                      class="form-input @error('notes') border-red-500 @enderror"
                                      placeholder="Enter any notes for this shift">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Schedule Shift
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Personal Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-neutral-600">Employee ID</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile?->employee_id ?? 'Not Set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600">Email</p>
                        <p class="font-medium text-neutral-900">{{ $staff->email }}</p>
                    </div>
                    @if($staff->phone)
                    <div>
                        <p class="text-sm text-neutral-600">Phone</p>
                        <p class="font-medium text-neutral-900">{{ $staff->phone }}</p>
                    </div>
                    @endif
                    @if($staff->date_of_birth)
                    <div>
                        <p class="text-sm text-neutral-600">Date of Birth</p>
                        <p class="font-medium text-neutral-900">{{ $staff->date_of_birth->format('F d, Y') }}</p>
                    </div>
                    @endif
                    @if($staff->gender)
                    <div>
                        <p class="text-sm text-neutral-600">Gender</p>
                        <p class="font-medium text-neutral-900">{{ ucfirst($staff->gender) }}</p>
                    </div>
                    @endif
                    @if($staff->address)
                    <div>
                        <p class="text-sm text-neutral-600">Address</p>
                        <p class="font-medium text-neutral-900">{{ $staff->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Employment</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-neutral-600">Department</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile?->department ?? 'Not Set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600">Position</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile?->position ?? 'Not Set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600">Date Hired</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile?->date_hired?->format('F d, Y') ?? 'Not Set' }}</p>
                    </div>
                    @if($staff->staffProfile?->salary)
                    <div>
                        <p class="text-sm text-neutral-600">Salary</p>
                        <p class="font-medium text-neutral-900">${{ number_format($staff->staffProfile->salary, 2) }}</p>
                    </div>
                    @endif
                    @if($staff->staffProfile?->qualifications)
                    <div>
                        <p class="text-sm text-neutral-600">Qualifications</p>
                        <p class="font-medium text-neutral-900 text-sm">{{ $staff->staffProfile->qualifications }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($staff->staffProfile?->emergency_contact_name || $staff->staffProfile?->emergency_contact_phone)
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Emergency Contact</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($staff->staffProfile?->emergency_contact_name)
                    <div>
                        <p class="text-sm text-neutral-600">Contact Name</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile->emergency_contact_name }}</p>
                    </div>
                    @endif
                    @if($staff->staffProfile?->emergency_contact_phone)
                    <div>
                        <p class="text-sm text-neutral-600">Contact Phone</p>
                        <p class="font-medium text-neutral-900">{{ $staff->staffProfile->emergency_contact_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($staff->staffProfile?->notes)
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-6 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-neutral-700">{{ $staff->staffProfile->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection