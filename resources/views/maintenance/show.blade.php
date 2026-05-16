<x-layouts.app>
@php
    $priorityBadge = match($maintenanceRequest->priority) {
        'urgent' => 'bg-red-100 text-red-700',
        'high'   => 'bg-orange-100 text-orange-700',
        'medium' => 'bg-amber-100 text-amber-700',
        default  => 'bg-blue-100 text-blue-700',
    };
    $statusBadge = match($maintenanceRequest->status) {
        'completed'   => 'bg-green-100 text-green-700',
        'in_progress' => 'bg-blue-100 text-blue-700',
        'cancelled'   => 'bg-neutral-100 text-neutral-500',
        default       => 'bg-amber-100 text-amber-700',
    };
    $statusLabel = match($maintenanceRequest->status) {
        'in_progress' => 'In Progress',
        default       => ucfirst($maintenanceRequest->status),
    };
    $isPrivileged = in_array(auth()->user()->role, ['admin', 'head_of_operations', 'head_of_schools', 'head_of_homes']);
@endphp
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back button + hero --}}
    <div class="flex items-start gap-3">
        <a href="{{ route('maintenance.index') }}" class="text-neutral-400 hover:text-neutral-600 transition-colors mt-1">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $priorityBadge }}">{{ ucfirst($maintenanceRequest->priority) }} Priority</span>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ $statusLabel }}</span>
            </div>
            <h2 class="text-2xl font-bold text-neutral-900 leading-tight">{{ $maintenanceRequest->title }}</h2>
            <p class="text-sm text-neutral-500 mt-0.5">
                <a href="{{ route('facilities.show', $maintenanceRequest->facility) }}" class="hover:text-[#324b45] transition-colors">{{ $maintenanceRequest->facility->name }}</a>
                · Request #{{ $maintenanceRequest->id }}
            </p>
        </div>
        @if($isPrivileged)
        <a href="{{ route('maintenance.edit_request', $maintenanceRequest) }}" class="btn btn-secondary btn-sm shrink-0">
            <i data-lucide="pencil" class="w-3.5 h-3.5 mr-1 inline-block"></i> Edit
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Detail grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Requested By</p>
            <p class="font-semibold text-neutral-900">{{ $maintenanceRequest->requestedBy->name }}</p>
            <p class="text-xs text-neutral-400 mt-0.5">{{ $maintenanceRequest->requested_date ? \Carbon\Carbon::parse($maintenanceRequest->requested_date)->format('d M Y') : '—' }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Assigned To</p>
            @if($maintenanceRequest->assignedTo)
                <p class="font-semibold text-neutral-900">{{ $maintenanceRequest->assignedTo->name }}</p>
                <p class="text-xs text-neutral-400 mt-0.5">{{ ucfirst(str_replace('_', ' ', $maintenanceRequest->assignedTo->role)) }}</p>
            @else
                <p class="font-semibold text-neutral-400 italic">Unassigned</p>
            @endif
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Due Date</p>
            @if($maintenanceRequest->due_date)
                @php $overdue = $maintenanceRequest->due_date < now()->toDateString() && !in_array($maintenanceRequest->status, ['completed','cancelled']); @endphp
                <p class="font-semibold {{ $overdue ? 'text-red-600' : 'text-neutral-900' }}">{{ \Carbon\Carbon::parse($maintenanceRequest->due_date)->format('d M Y') }}</p>
                @if($overdue)<p class="text-xs text-red-500 mt-0.5">Overdue</p>@endif
            @else
                <p class="font-semibold text-neutral-400 italic">Not set</p>
            @endif
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Estimated Cost</p>
            <p class="font-semibold text-neutral-900">{{ $maintenanceRequest->estimated_cost ? number_format($maintenanceRequest->estimated_cost, 2) : '—' }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Actual Cost</p>
            <p class="font-semibold text-neutral-900">{{ $maintenanceRequest->actual_cost ? number_format($maintenanceRequest->actual_cost, 2) : '—' }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <p class="text-xs text-neutral-500 mb-1">Completed Date</p>
            <p class="font-semibold text-neutral-900">{{ $maintenanceRequest->completed_date ? \Carbon\Carbon::parse($maintenanceRequest->completed_date)->format('d M Y') : '—' }}</p>
        </div>
    </div>

    {{-- Description --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-3">Description</h3>
        <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $maintenanceRequest->description }}</p>
    </div>

    {{-- Resolution --}}
    @if($maintenanceRequest->resolution)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-3">Resolution / Notes</h3>
        <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $maintenanceRequest->resolution }}</p>
    </div>
    @endif

    {{-- Privileged: update status form --}}
    @if($isPrivileged)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-100">
        <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wide mb-4">Update Request</h3>
        <form action="{{ route('maintenance.update_status', $maintenanceRequest) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input w-full" required>
                        <option value="pending"     {{ $maintenanceRequest->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $maintenanceRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ $maintenanceRequest->status === 'completed'   ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"   {{ $maintenanceRequest->status === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-input w-full" required>
                        <option value="low"    {{ $maintenanceRequest->priority === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $maintenanceRequest->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ $maintenanceRequest->priority === 'high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $maintenanceRequest->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Assign To</label>
                    <select name="assigned_to" class="form-input w-full">
                        <option value="">— Unassigned —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $maintenanceRequest->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" value="{{ $maintenanceRequest->due_date }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Estimated Cost</label>
                    <input type="number" name="estimated_cost" value="{{ $maintenanceRequest->estimated_cost }}" step="0.01" min="0" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Actual Cost</label>
                    <input type="number" name="actual_cost" value="{{ $maintenanceRequest->actual_cost }}" step="0.01" min="0" class="form-input w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Resolution / Notes</label>
                    <textarea name="resolution" rows="3" class="form-input w-full" placeholder="Document resolution steps, findings, or notes…">{{ $maintenanceRequest->resolution }}</textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4 mr-1 inline-block"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
    @endif

</div>
</x-layouts.app>
