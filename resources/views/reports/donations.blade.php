<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Donations Report</h2>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-xl font-bold text-green-600">{{ number_format($stats['total_received'], 2) }}</p>
            <p class="text-xs text-neutral-600">Total Received</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-xl font-bold text-blue-600">{{ number_format($stats['this_year'], 2) }}</p>
            <p class="text-xs text-neutral-600">This Year</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-xl font-bold text-purple-600">{{ number_format($stats['this_month'], 2) }}</p>
            <p class="text-xs text-neutral-600">This Month</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center">
            <p class="text-xl font-bold text-neutral-700">{{ $stats['total_count'] }}</p>
            <p class="text-xs text-neutral-600">Total Donations</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border text-center col-span-2 md:col-span-1">
            @foreach($stats['by_type'] as $type => $total)
            <p class="text-xs"><span class="font-medium">{{ ucfirst($type) }}:</span> {{ number_format($total, 2) }}</p>
            @endforeach
            <p class="text-xs text-neutral-500">By Type</p>
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md border">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <select name="type" class="form-input">
                <option value="">All Types</option>
                <option value="cash" {{ request('type') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="material" {{ request('type') === 'material' ? 'selected' : '' }}>Material</option>
                <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
            </select>
            <select name="status" class="form-input">
                <option value="">All Statuses</option>
                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                <option value="pledged" {{ request('status') === 'pledged' ? 'selected' : '' }}>Pledged</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input">
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.donations') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Date</th><th>Donor</th><th>Type</th><th>Amount</th><th>Status</th><th>Receipt</th></tr></thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                        <td>{{ $donation->donor->name ?? '—' }}</td>
                        <td>{{ $donation->donation_type_label }}</td>
                        <td>{{ $donation->amount ? number_format($donation->amount, 2) : '—' }}</td>
                        <td><span class="badge {{ $donation->status === 'received' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($donation->status) }}</span></td>
                        <td class="text-sm text-neutral-500">{{ $donation->receipt_number }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-neutral-500">No donations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $donations->links() }}</div>
    </div>
</div>
</x-layouts.app>
