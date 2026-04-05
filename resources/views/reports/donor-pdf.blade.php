<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Report — {{ $donor->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h1 { font-size: 20px; } h2 { font-size: 15px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; }
        .label { color: #666; width: 35%; }
    </style>
</head>
<body>
    <h1>Donor Report: {{ $donor->name }}</h1>
    <p>Generated: {{ now()->format('M d, Y H:i') }}</p>

    <h2>Donor Information</h2>
    <table>
        <tr><td class="label">Type</td><td>{{ ucfirst($donor->donor_type) }}</td></tr>
        <tr><td class="label">Status</td><td>{{ ucfirst($donor->status) }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $donor->email ?? '—' }}</td></tr>
        <tr><td class="label">Phone</td><td>{{ $donor->phone ?? '—' }}</td></tr>
        <tr><td class="label">Address</td><td>{{ $donor->address ?? '—' }}</td></tr>
        <tr><td class="label">Tax ID</td><td>{{ $donor->tax_id ?? '—' }}</td></tr>
        <tr><td class="label">Managed By</td><td>{{ $donor->managedBy->name ?? '—' }}</td></tr>
    </table>

    <h2>Donation Summary</h2>
    <table>
        <tr><td class="label">Total Donations</td><td>{{ $donor->donations->count() }}</td></tr>
        <tr><td class="label">Total Amount Received</td><td>{{ number_format($donor->getTotalDonationsAllTime(), 2) }}</td></tr>
        <tr><td class="label">This Year</td><td>{{ number_format($donor->getTotalDonationsThisYear(), 2) }}</td></tr>
        <tr><td class="label">Last Donation</td><td>{{ $donor->getLastDonationDate()?->format('M d, Y') ?? '—' }}</td></tr>
        <tr><td class="label">Donation Frequency</td><td>{{ ucfirst($donor->getDonationFrequency()) }}</td></tr>
    </table>

    @if($donor->donations->count())
    <h2>Donation History</h2>
    <table>
        <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Status</th><th>Receipt</th></tr></thead>
        <tbody>
            @foreach($donor->donations as $donation)
            <tr>
                <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                <td>{{ $donation->donation_type_label }}</td>
                <td>{{ $donation->amount ? number_format($donation->amount, 2) : '—' }}</td>
                <td>{{ ucfirst($donation->status) }}</td>
                <td>{{ $donation->receipt_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
