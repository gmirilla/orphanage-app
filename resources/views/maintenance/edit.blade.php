@section('title', 'Edit Maintenance Request')

<x-layouts.app>
    <div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="container">
        <div class="bg-white rounded-lg shadow-md border border-neutral-100">
            <div class="p-4 rounded-t-lg">

                <h2>Maintenance Request : for {{ $maintenanceRequest->facility->name }}</h2>
                <form action="{{route('maintenance.update_status', $maintenanceRequest->id)}}" method="post">
                    @csrf
 

                <!-- REQUEST TITLE -->
                <div class="mb-3">
                    <label for="title" class="form-label" required><b>Title</b></label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $maintenanceRequest->title) }}" disabled>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- REQUEST Priority -->
                <div class="mb-3 flex justify-between">
                    <div>
                        <label for="requested_by" class="form-label" required>Requested By:</label>
                        <input type="text" name="requested_by" id="requested_by" class="form-control"
                            value="{{ old('requested_by', $maintenanceRequest->requestedBy->name) }}" disabled>
                        @error('requested_by')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="priority" class="form-label" required>Priority</label>
                        <select name="priority" id="priority" class="form-control form-select">
                            <option value="low"
                                {{ old('priority', $maintenanceRequest->priority) == 'low' ? 'selected' : '' }}>Low
                            </option>
                            <option value="medium"
                                {{ old('priority', $maintenanceRequest->priority) == 'medium' ? 'selected' : '' }}>
                                Medium</option>
                            <option value="high"
                                {{ old('priority', $maintenanceRequest->priority) == 'high' ? 'selected' : '' }}>High
                            </option>
                            <option value="urgent"
                                {{ old('priority', $maintenanceRequest->priority) == 'urgent' ? 'selected' : '' }}>
                                Urgent</option>
                        </select>

                        @error('priority')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="status" class="form-label" required>Status</label>
                        <select name="status" id="status" class="form-control form-select">
                            <option value="pending"
                                {{ old('status', $maintenanceRequest->status) == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="in_progress"
                                {{ old('status', $maintenanceRequest->status) == 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="completed"
                                {{ old('status', $maintenanceRequest->status) == 'completed' ? 'selected' : '' }}>
                                Completed</option>
                            <option value="cancelled"
                                {{ old('status', $maintenanceRequest->status) == 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="requested_date" class="form-label" required>Requested Date</label>
                        <input type="text" name="requested_date" id="requested_date" class="form-control"
                            value="{{ old('requested_date', $maintenanceRequest->requested_date) }}" disabled>
                        @error('requested_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <!-- REQUEST description -->
                <div class="mb-3">
                    <label for="description" class="form-label" required>Description</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="5" disabled>{{ old('description', $maintenanceRequest->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- REQUEST reolution/note -->
                <div class="mb-3">
                    <label for="resolution" class="form-label" required>Resolution</label>
                    <textarea class="form-control" name="resolution" id="resolution" cols="30" rows="5">{{ old('resolution', $maintenanceRequest->resolution) }}</textarea>
                    @error('resolution')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- REQUEST Priority -->
                <div class="mb-3 flex justify-between">
                    <div>
                        <label for="assigned_to" class="form-label" required>Assigned To</label>
                        <select class="form-control form-select" name="assigned_to" id="assigned_to">
                            <option value=""> -- Select Staff --</option>
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $maintenanceRequest->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                
                            @empty
                                
                            @endforelse
                        </select>
                        @error('assigned_to')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="due_date" class="form-label" required>Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date',($maintenanceRequest->due_date)) }}">
                        @error('due_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="estimated_cost" class="form-label" required>Estimated Cost</label>
                        <input type="text" name="estimated_cost" id="estimated_cost" class="form-control"
                            value="{{ old('estimated_cost', $maintenanceRequest->estimated_cost) }}">
                        @error('estimated_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="actual_cost" class="form-label" required>Actual Cost</label>
                        <input type="text" name="actual_cost" id="actual_cost" class="form-control"
                            value="{{ old('actual_cost', $maintenanceRequest->actual_cost) }}">
                        @error('actual_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="completed_date" class="form-label" required>Completed Date</label>
                        <input type="date" name="completed_date" id="completed_date" class="form-control"
                            value="{{ old('completed_date', $maintenanceRequest->completed_date) }}"  disabled>
                        @error('completed_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3 flex justify-end">
                    <button type="submit" class="btn btn-secondary mr-4"><i
                            class="fa fa-save"></i> Update Request</button>

                    <a href="{{ route('maintenance.edit_request', $maintenanceRequest->id) }}"
                        class="btn btn-success"><i class="fa fa-edit"></i> Edit Request</a>

                </div>
                               </form>
            </div>
        </div>



</x-layouts.app>
