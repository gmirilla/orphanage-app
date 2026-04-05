<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Donation History</h2>
            <p class="text-sm text-neutral-600">{{ $donor->name }}</p>
        </div>
        <a href="{{ route('donors.show', $donor) }}" class="btn btn-secondary">Back to Donor</a>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Year</label>
                <input type="number" name="year" value="{{ request('year') }}" placeholder="e.g. 2024" class="form-input w-full" min="2000" max="{{ date('Y') }}">
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="type" class="form-input w-full">
                    <option value="">All Types</option>
                    <option value="cash" {{ request('type') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="material" {{ request('type') === 'material' ? 'selected' : '' }}>Material</option>
                    <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('donors.donation-history', $donor) }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Status</th><th>Description</th><th>Receipt</th><th>Recorded By</th></tr></thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                        <td>{{ $donation->donation_type_label }}</td>
                        <td>{{ $donation->amount ? number_format($donation->amount, 2) : '—' }}</td>
                        <td><span class="badge {{ $donation->status === 'received' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($donation->status) }}</span></td>
                        <td>{{ $donation->description ?? '—' }}</td>
                        <td class="text-sm text-neutral-500">{{ $donation->receipt_number }}</td>
                        <td>{{ $donation->recordedBy->name ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-neutral-500 py-8">No donations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $donations->links() }}</div>
    </div>
</div>
</x-layouts.app>
