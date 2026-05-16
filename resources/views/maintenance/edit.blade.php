<x-layouts.app>
@php
    $isPrivileged = in_array(auth()->user()->role, ['admin', 'head_of_operations', 'head_of_schools', 'head_of_homes']);
@endphp
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('maintenance.view', $maintenanceRequest) }}" class="text-neutral-400 hover:text-neutral-600 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Edit Request</h2>
            <p class="text-sm text-neutral-500">{{ $maintenanceRequest->facility->name }} · #{{ $maintenanceRequest->id }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Read-only context --}}
    <div class="bg-[#324b45]/5 border border-[#324b45]/20 rounded-xl p-4">
        <p class="text-sm font-semibold text-[#324b45]">{{ $maintenanceRequest->title }}</p>
        <p class="text-xs text-neutral-600 mt-1 line-clamp-2">{{ $maintenanceRequest->description }}</p>
        <p class="text-xs text-neutral-500 mt-2">Submitted by {{ $maintenanceRequest->requestedBy->name }} on {{ \Carbon\Carbon::parse($maintenanceRequest->requested_date)->format('d M Y') }}</p>
    </div>

    <form action="{{ route('maintenance.update_status', $maintenanceRequest) }}" method="POST" class="space-y-5">
        @csrf

        {{-- Status & Priority --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Status & Priority</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input w-full" required>
                        <option value="pending"     {{ old('status', $maintenanceRequest->status) === 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $maintenanceRequest->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ old('status', $maintenanceRequest->status) === 'completed'   ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"   {{ old('status', $maintenanceRequest->status) === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="form-input w-full" required>
                        <option value="low"    {{ old('priority', $maintenanceRequest->priority) === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $maintenanceRequest->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority', $maintenanceRequest->priority) === 'high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $maintenanceRequest->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Assignment & Scheduling --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Assignment & Scheduling</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Assign To</label>
                    <select name="assigned_to" class="form-input w-full">
                        <option value="">— Unassigned —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $maintenanceRequest->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $maintenanceRequest->due_date) }}" class="form-input w-full">
                    @error('due_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Costs --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Cost Tracking</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Estimated Cost</label>
                    <input type="number" name="estimated_cost" step="0.01" min="0"
                           value="{{ old('estimated_cost', $maintenanceRequest->estimated_cost) }}"
                           class="form-input w-full" placeholder="0.00">
                    @error('estimated_cost')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Actual Cost</label>
                    <input type="number" name="actual_cost" step="0.01" min="0"
                           value="{{ old('actual_cost', $maintenanceRequest->actual_cost) }}"
                           class="form-input w-full" placeholder="0.00">
                    @error('actual_cost')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Resolution --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100 space-y-4">
            <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide">Resolution / Notes</h3>
            <textarea name="resolution" rows="4" class="form-input w-full"
                      placeholder="Document steps taken, findings, or resolution notes…">{{ old('resolution', $maintenanceRequest->resolution) }}</textarea>
            @error('resolution')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('maintenance.view', $maintenanceRequest) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Changes
            </button>
        </div>
    </form>
</div>
</x-layouts.app>
