<x-layouts.app>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $donor->name }}</h2>
            <p class="text-sm text-neutral-600">{{ ucfirst($donor->donor_type) }} &bull; <span class="badge {{ $donor->status === 'active' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($donor->status) }}</span></p>
        </div>
        <div class="flex space-x-2 mt-4 sm:mt-0">
            <a href="{{ route('donors.edit', $donor) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('donors.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-sm text-neutral-600">Total Donations</p>
            <p class="text-2xl font-bold text-neutral-900">{{ $totalDonations }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-sm text-neutral-600">Total Amount</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($totalAmount, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-sm text-neutral-600">This Year (Count)</p>
            <p class="text-2xl font-bold text-neutral-900">{{ $thisYearDonations }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-sm text-neutral-600">This Year (Amount)</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($thisYearAmount, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Donor Info -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500">Email</dt><dd>{{ $donor->email ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Phone</dt><dd>{{ $donor->phone ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Address</dt><dd>{{ $donor->address ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Tax ID</dt><dd>{{ $donor->tax_id ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500">Managed By</dt><dd>{{ $donor->managedBy->name ?? '—' }}</dd></div>
            </dl>
        </div>

        <!-- Add Donation Form -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100 md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Record Donation</h3>
            @if($errors->any())
                <div class="alert alert-danger text-sm mb-3">
                    <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('donors.add-donation', $donor) }}" class="grid grid-cols-2 gap-3">
                @csrf
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <select name="donation_type" class="form-input w-full" required>
                        <option value="cash">Cash</option>
                        <option value="material">Materials / Goods</option>
                        <option value="service">Services</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" step="0.01" min="0" class="form-input w-full" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="donation_date" value="{{ date('Y-m-d') }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input w-full">
                        <option value="received">Received</option>
                        <option value="pledged">Pledged</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="2" class="form-input w-full" placeholder="Optional details..."></textarea>
                </div>
                <div class="col-span-2 flex justify-end">
                    <button type="submit" class="btn btn-primary">Record Donation</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="bg-white rounded-lg shadow-md border border-neutral-100">
        <div class="p-6 border-b border-neutral-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Recent Donations</h3>
            <a href="{{ route('donors.donation-history', $donor) }}" class="text-sm text-blue-600">View all</a>
        </div>
        @if($donor->donations->count())
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Status</th><th>Receipt</th></tr></thead>
                <tbody>
                    @foreach($donor->donations as $donation)
                    <tr>
                        <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                        <td>{{ $donation->donation_type_label }}</td>
                        <td>{{ $donation->amount ? number_format($donation->amount, 2) : '—' }}</td>
                        <td><span class="badge {{ $donation->status === 'received' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($donation->status) }}</span></td>
                        <td class="text-sm text-neutral-500">{{ $donation->receipt_number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="p-6 text-neutral-500">No donations recorded yet.</p>
        @endif
    </div>
</div>
</x-layouts.app>
