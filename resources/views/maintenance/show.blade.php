@section('title', 'Maintenance Request')

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
                        <input type="text" name="priority" id="priority" class="form-control"
                            value="{{ old('priority', ucfirst($maintenanceRequest->priority)) }}" disabled>
                        @error('priority')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="status" class="form-label" required>Status</label>
                        <input type="text" name="status" id="status" class="form-control"
                            value="{{ old('status', ucfirst($maintenanceRequest->status)) }}" disabled>
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
                    <textarea class="form-control" name="resolution" id="resolution" cols="30" rows="5" disabled>{{ old('resolution', $maintenanceRequest->resolution) }}</textarea>
                    @error('resolution')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- REQUEST Priority -->
                <div class="mb-3 flex justify-between">
                    <div>
                        <label for="assigned_to" class="form-label" required>Assigned To</label>
                        <input type="text" name="assigned_to" id="assigned_to" class="form-control"
                            value="{{ old('assigned_to', $maintenanceRequest->assignedTo->name ?? 'Unassigned') }}"
                            disabled>
                        @error('assigned_to')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="due_date" class="form-label" required>Due Date</label>
                        <input type="text" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date', ucfirst($maintenanceRequest->due_date)) }}" disabled>
                        @error('due_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="estimated_cost" class="form-label" required>Estimated Cost</label>
                        <input type="text" name="estimated_cost" id="estimated_cost" class="form-control"
                            value="{{ old('estimated_cost', $maintenanceRequest->estimated_cost) }}" disabled>
                        @error('estimated_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="actual_cost" class="form-label" required>Actual Cost</label>
                        <input type="text" name="actual_cost" id="actual_cost" class="form-control"
                            value="{{ old('actual_cost', $maintenanceRequest->actual_cost) }}" disabled>
                        @error('actual_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div>
                        <label for="completed_date" class="form-label" required>Completed Date</label>
                        <input type="text" name="completed_date" id="completed_date" class="form-control"
                            value="{{ old('completed_date', $maintenanceRequest->completed_date) }}" disabled>
                        @error('completed_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3 flex justify-end">
                    <a href="{{route ('maintenance.index')}}" class="btn btn-secondary mr-4"><i class="fa fa-arrow-left"></i> Back to List</a>

                    <a href="{{route ('maintenance.edit_request', $maintenanceRequest->id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Edit Request</a>
            
            </div>
        </div>
    </div>



</x-layouts.app>
