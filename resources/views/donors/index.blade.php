<x-layouts.app>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Donors</h2>
            <p class="text-sm text-neutral-600">Manage donor records and donations</p>
        </div>
        <a href="{{ route('donors.create') }}" class="btn btn-primary mt-4 sm:mt-0">
            <i class="fa fa-plus mr-2"></i> Add Donor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="form-input w-full">
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="type" class="form-input w-full">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="preferred" {{ request('status') === 'preferred' ? 'selected' : '' }}>Preferred</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('donors.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-semibold">Donor List ({{ $donors->total() }})</h3>
        </div>
        @if($donors->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Donations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donors as $donor)
                    <tr>
                        <td>
                            <p class="font-medium text-neutral-900">{{ $donor->name }}</p>
                            <p class="text-sm text-neutral-500">#{{ $donor->id }}</p>
                        </td>
                        <td>{{ ucfirst($donor->donor_type) }}</td>
                        <td>
                            <p>{{ $donor->email ?? '—' }}</p>
                            <p class="text-sm text-neutral-500">{{ $donor->phone ?? '' }}</p>
                        </td>
                        <td>
                            <span class="badge {{ $donor->status === 'active' ? 'badge-success' : ($donor->status === 'preferred' ? 'badge-primary' : 'badge-secondary') }}">
                                {{ ucfirst($donor->status) }}
                            </span>
                        </td>
                        <td>{{ $donor->donations_count }}</td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('donors.show', $donor) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('donors.edit', $donor) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('donors.destroy', $donor) }}" onsubmit="return confirm('Deactivate this donor?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $donors->links() }}</div>
        @else
        <div class="p-12 text-center">
            <p class="text-neutral-600">No donors found. <a href="{{ route('donors.create') }}" class="text-blue-600">Add the first donor.</a></p>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
