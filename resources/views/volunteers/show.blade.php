<x-layouts.app>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $volunteer->name }}</h2>
            @php $status = $volunteer->volunteerProfile->status ?? 'pending'; @endphp
            <span class="badge {{ $status === 'approved' ? 'badge-success' : ($status === 'suspended' ? 'badge-danger' : 'badge-warning') }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        <div class="flex space-x-2 mt-4 sm:mt-0">
            <a href="{{ route('volunteers.edit', $volunteer) }}" class="btn btn-secondary">Edit</a>
            @if($status !== 'approved')
            <form method="POST" action="{{ route('volunteers.approve', $volunteer) }}">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>
            @endif
            <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Info Card -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100 space-y-3">
            <h3 class="font-semibold text-neutral-900 border-b pb-2">Contact Details</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500">Email</dt><dd>{{ $volunteer->email }}</dd></div>
                <div><dt class="text-neutral-500">Phone</dt><dd>{{ $volunteer->phone ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Gender</dt><dd>{{ ucfirst($volunteer->gender ?? '—') }}</dd></div>
                <div><dt class="text-neutral-500">Date of Birth</dt><dd>{{ $volunteer->date_of_birth?->format('M d, Y') ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Address</dt><dd>{{ $volunteer->address ?? '—' }}</dd></div>
            </dl>

            <h3 class="font-semibold text-neutral-900 border-b pb-2 pt-2">Volunteer Profile</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500">Volunteer ID</dt><dd>{{ $volunteer->volunteerProfile->volunteer_id ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Availability</dt><dd>{{ ucfirst($volunteer->volunteerProfile->availability ?? '—') }}</dd></div>
                <div><dt class="text-neutral-500">Skills</dt><dd>{{ $volunteer->volunteerProfile->skills ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Registered</dt><dd>{{ $volunteer->volunteerProfile->registration_date?->format('M d, Y') ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Approved On</dt><dd>{{ $volunteer->volunteerProfile->approval_date?->format('M d, Y') ?? '—' }}</dd></div>
            </dl>
        </div>

        <!-- Assign Task + Task List -->
        <div class="md:col-span-2 space-y-6">
            <!-- Assign Task Form -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-4">Assign Task</h3>
                @if($errors->any())
                    <div class="alert alert-danger text-sm mb-3">
                        <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('volunteers.assign-task', $volunteer) }}" class="grid grid-cols-2 gap-3">
                    @csrf
                    <div class="col-span-2">
                        <label class="form-label">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" class="form-input w-full" required>
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="2" class="form-input w-full" required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" class="form-input w-full" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-input w-full">
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <button type="submit" class="btn btn-primary">Assign Task</button>
                    </div>
                </form>
            </div>

            <!-- Task History -->
            <div class="bg-white rounded-lg shadow-md border border-neutral-100">
                <div class="p-4 border-b border-neutral-200">
                    <h3 class="font-semibold text-neutral-900">Task History</h3>
                </div>
                @if($volunteer->volunteerTasks->count())
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead><tr><th>Title</th><th>Priority</th><th>Status</th><th>Due</th><th>Rating</th></tr></thead>
                        <tbody>
                            @foreach($volunteer->volunteerTasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ ucfirst($task->priority ?? '—') }}</td>
                                <td><span class="badge {{ $task->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($task->status ?? '—') }}</span></td>
                                <td>{{ $task->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td>{{ $task->rating ? $task->rating . '/5' : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="p-6 text-neutral-500 text-sm">No tasks assigned yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
