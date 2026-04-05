<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div><h2 class="text-2xl font-bold text-neutral-900">Maintenance Report</h2></div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach([
            ['label' => 'Total', 'value' => $stats['total'], 'color' => 'text-neutral-700'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-orange-600'],
            ['label' => 'In Progress', 'value' => $stats['in_progress'], 'color' => 'text-blue-600'],
            ['label' => 'Completed', 'value' => $stats['completed'], 'color' => 'text-green-600'],
            ['label' => 'Overdue', 'value' => $stats['overdue'], 'color' => 'text-red-600'],
            ['label' => 'Total Cost', 'value' => number_format($stats['total_cost'], 2), 'color' => 'text-purple-600'],
        ] as $stat)
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
            <p class="text-xs text-neutral-600">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md border">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <select name="status" class="form-input">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <select name="priority" class="form-input">
                <option value="">All Priorities</option>
                @foreach(['low','medium','high','urgent'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
            <select name="facility_id" class="form-input">
                <option value="">All Facilities</option>
                @foreach($facilities as $facility)
                <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>{{ $facility->name }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input">
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.maintenance') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Title</th><th>Facility</th><th>Priority</th><th>Status</th><th>Requested</th><th>Due</th><th>Cost</th></tr></thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td>
                            <a href="{{ route('maintenance.view', $req) }}" class="text-blue-600">{{ $req->title }}</a>
                            <p class="text-xs text-neutral-500">By: {{ $req->requestedBy->name ?? '—' }}</p>
                        </td>
                        <td>{{ $req->facility->name ?? '—' }}</td>
                        <td><span class="badge {{ $req->priority === 'urgent' ? 'badge-danger' : ($req->priority === 'high' ? 'badge-warning' : 'badge-secondary') }}">{{ ucfirst($req->priority) }}</span></td>
                        <td><span class="badge {{ $req->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span></td>
                        <td>{{ $req->requested_date->format('M d, Y') }}</td>
                        <td>{{ $req->due_date?->format('M d, Y') ?? '—' }}</td>
                        <td>{{ $req->actual_cost ? number_format($req->actual_cost, 2) : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-neutral-500">No requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $requests->links() }}</div>
    </div>
</div>
</x-layouts.app>
