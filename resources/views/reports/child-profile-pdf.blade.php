<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Child Profile — {{ $child->name }}</title>
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
    <h1>Child Profile: {{ $child->name }}</h1>
    <p>Generated: {{ now()->format('M d, Y H:i') }}</p>

    <h2>Personal Information</h2>
    <table>
        <tr><td class="label">Gender</td><td>{{ ucfirst($child->gender) }}</td></tr>
        <tr><td class="label">Date of Birth</td><td>{{ $child->date_of_birth->format('M d, Y') }} (Age {{ $child->age }})</td></tr>
        <tr><td class="label">Blood Group</td><td>{{ $child->blood_group ?? '—' }}</td></tr>
        <tr><td class="label">Height</td><td>{{ $child->height_cm ? $child->height_cm . ' cm' : '—' }}</td></tr>
        <tr><td class="label">Weight</td><td>{{ $child->weight_kg ? $child->weight_kg . ' kg' : '—' }}</td></tr>
        <tr><td class="label">Special Needs</td><td>{{ $child->special_needs ?? '—' }}</td></tr>
        <tr><td class="label">Guardianship</td><td>{{ $child->guardianship_status ?? '—' }}</td></tr>
    </table>

    <h2>Admission</h2>
    <table>
        <tr><td class="label">Admission Date</td><td>{{ $child->admission_date->format('M d, Y') }}</td></tr>
        <tr><td class="label">Source</td><td>{{ $child->admission_source }}</td></tr>
        <tr><td class="label">Admitted By</td><td>{{ $child->admittedBy->name ?? '—' }}</td></tr>
        <tr><td class="label">Room</td><td>{{ $child->currentRoomAssignment?->roomAllocation?->room_number ?? 'Unassigned' }}</td></tr>
        <tr><td class="label">Background</td><td>{{ $child->background_summary }}</td></tr>
    </table>

    @if($child->educationHistories->count())
    <h2>Education History</h2>
    <table>
        <thead><tr><th>School</th><th>Level</th><th>Status</th><th>From</th><th>To</th></tr></thead>
        <tbody>
            @foreach($child->educationHistories as $edu)
            <tr>
                <td>{{ $edu->school_name }}</td>
                <td>{{ ucfirst($edu->education_level) }}</td>
                <td>{{ ucfirst($edu->status) }}</td>
                <td>{{ $edu->start_date?->format('M Y') }}</td>
                <td>{{ $edu->end_date?->format('M Y') ?? 'Present' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($child->milestones->count())
    <h2>Milestones</h2>
    <table>
        <thead><tr><th>Date</th><th>Type</th><th>Title</th><th>Description</th></tr></thead>
        <tbody>
            @foreach($child->milestones as $m)
            <tr>
                <td>{{ $m->date_recorded?->format('M d, Y') }}</td>
                <td>{{ ucfirst($m->type) }}</td>
                <td>{{ $m->title }}</td>
                <td>{{ $m->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
