@extends('layouts.app')

@section('title', 'Shift Schedule - ' . $staff->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Shift Schedule - {{ $staff->name }}</h2>
            <p class="text-sm text-neutral-600">View and manage {{ $staff->name }}'s shift schedule</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-2">
            <a href="{{ route('staff.show', $staff) }}" class="btn btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Profile
            </a>
            <a href="{{ route('staff.edit', $staff) }}" class="btn btn-primary">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Edit Staff
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="month" class="form-label">Month</label>
                <select id="month" name="month" class="form-input w-40">
                    <option value="">All Months</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label for="year" class="form-label">Year</label>
                <select id="year" name="year" class="form-input w-32">
                    <option value="">All Years</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-input w-40">
                    <option value="">All Status</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                Filter
            </button>
            
            <a href="{{ route('staff.shifts', $staff) }}" class="btn btn-secondary">
                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                Clear
            </a>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Total Shifts</p>
                    <p class="text-lg font-semibold text-neutral-900">{{ $shifts->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Completed</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $shifts->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">Upcoming</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $shifts->where('shift_date', '>=', now())->where('status', 'scheduled')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-neutral-600">This Month</p>
                    <p class="text-lg font-semibold text-neutral-900">
                        {{ $shifts->where('shift_date', '>=', now()->startOfMonth())->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shifts List -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900">Shift Schedule</h3>
        </div>
        
        @if($shifts->count() > 0)
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Shift Type</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium text-neutral-900">
                                    {{ $shift->shift_date->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-neutral-600">
                                    {{ $shift->shift_date->format('l') }}
                                </p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p class="font-medium text-neutral-900">
                                    {{ date('g:i A', strtotime($shift->start_time)) }}
                                </p>
                                <p class="text-sm text-neutral-600">
                                    to {{ date('g:i A', strtotime($shift->end_time)) }}
                                </p>
                            </div>
                        </td>
                        <td>
                            @if($shift->shift_type)
                                <span class="badge badge-primary">
                                    {{ ucfirst(str_replace('_', ' ', $shift->shift_type)) }}
                                </span>
                            @else
                                <span class="text-sm text-neutral-400">Not specified</span>
                            @endif
                        </td>
                        <td class="text-sm text-neutral-600">
                            {{ $shift->department ?? 'Not specified' }}
                        </td>
                        <td>
                            <span class="badge badge-{{ 
                                $shift->status === 'completed' ? 'success' : 
                                ($shift->status === 'scheduled' ? 'info' : 
                                ($shift->status === 'in_progress' ? 'warning' : 
                                ($shift->status === 'absent' ? 'danger' : 'secondary')))
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $shift->status)) }}
                            </span>
                        </td>
                        <td class="text-sm text-neutral-600 max-w-xs">
                            @if($shift->notes)
                                <div class="truncate" title="{{ $shift->notes }}">
                                    {{ $shift->notes }}
                                </div>
                            @else
                                <span class="text-neutral-400">No notes</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <button onclick="editShift({{ $shift->id }})" 
                                        class="text-blue-600 hover:text-blue-800 p-1"
                                        title="Edit Shift">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($shift->status === 'scheduled' || $shift->status === 'in_progress')
                                <select onchange="updateShiftStatus({{ $shift->id }}, this.value)" 
                                        class="text-xs form-input border-none p-1 min-w-0 w-auto">
                                    <option value="" {{ $shift->status ? '' : 'selected' }}>Change Status</option>
                                    <option value="scheduled" {{ $shift->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ $shift->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $shift->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="absent" {{ $shift->status === 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="cancelled" {{ $shift->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-neutral-200">
            {{ $shifts->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i data-lucide="calendar" class="w-16 h-16 text-neutral-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No shifts found</h3>
            <p class="text-neutral-600 mb-6">No shift schedules match your current filters.</p>
            <a href="{{ route('staff.show', $staff) }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Schedule First Shift
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Edit Shift Modal -->
<div id="editShiftModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-neutral-900 mb-4">Edit Shift</h3>
        <form id="editShiftForm">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_shift_type" class="form-label">Shift Type</label>
                    <select id="edit_shift_type" name="shift_type" class="form-input w-full">
                        <option value="morning">Morning (6AM - 2PM)</option>
                        <option value="afternoon">Afternoon (2PM - 10PM)</option>
                        <option value="night">Night (10PM - 6AM)</option>
                        <option value="full_day">Full Day (8AM - 8PM)</option>
                        <option value="part_time">Part Time</option>
                    </select>
                </div>
                
                <div>
                    <label for="edit_start_time" class="form-label">Start Time</label>
                    <input type="time" id="edit_start_time" name="start_time" class="form-input w-full">
                </div>
                
                <div>
                    <label for="edit_end_time" class="form-label">End Time</label>
                    <input type="time" id="edit_end_time" name="end_time" class="form-input w-full">
                </div>
                
                <div>
                    <label for="edit_department" class="form-label">Department</label>
                    <input type="text" id="edit_department" name="department" class="form-input w-full">
                </div>
                
                <div>
                    <label for="edit_notes" class="form-label">Notes</label>
                    <textarea id="edit_notes" name="notes" rows="2" class="form-input w-full"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditShiftModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Shift</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let editingShiftId = null;

function editShift(shiftId) {
    // Fetch shift data and populate modal
    // This would typically involve an AJAX call to get the shift details
    editingShiftId = shiftId;
    document.getElementById('editShiftModal').classList.remove('hidden');
}

function closeEditShiftModal() {
    document.getElementById('editShiftModal').classList.add('hidden');
    editingShiftId = null;
}

function updateShiftStatus(shiftId, status) {
    if (!status) return;
    
    // Create a form to submit the status update
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/shifts/${shiftId}/status`;
    form.innerHTML = `
        @csrf
        <input type="hidden" name="status" value="${status}">
    `;
    
    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('editShiftModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditShiftModal();
    }
});
</script>
@endpush
@endsection