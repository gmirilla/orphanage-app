<x-layouts.app>
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Reports</h2>
        <p class="text-sm text-neutral-600">View and export operational reports</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $summary['total_children'] }}</p>
            <p class="text-sm text-neutral-600">Active Children</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $summary['total_donors'] }}</p>
            <p class="text-sm text-neutral-600">Donors</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-green-600">{{ number_format($summary['total_donations'], 2) }}</p>
            <p class="text-sm text-neutral-600">Total Received</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-purple-600">{{ $summary['total_staff'] }}</p>
            <p class="text-sm text-neutral-600">Active Staff</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-neutral-700">{{ $summary['total_facilities'] }}</p>
            <p class="text-sm text-neutral-600">Facilities</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md border border-neutral-100 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $summary['pending_maintenance'] }}</p>
            <p class="text-sm text-neutral-600">Pending Maintenance</p>
        </div>
    </div>

    <!-- Report Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['route' => 'reports.children',    'title' => 'Children Report',     'icon' => 'users',     'desc' => 'Active children, admissions, demographics'],
            ['route' => 'reports.donations',   'title' => 'Donations Report',    'icon' => 'dollar-sign','desc' => 'Donation history and financial summary'],
            ['route' => 'reports.staff',       'title' => 'Staff Report',        'icon' => 'briefcase', 'desc' => 'Staff list, roles, and activity'],
            ['route' => 'reports.facilities',  'title' => 'Facilities Report',   'icon' => 'home',      'desc' => 'Facility inventory and occupancy'],
            ['route' => 'reports.maintenance', 'title' => 'Maintenance Report',  'icon' => 'tool',      'desc' => 'Maintenance requests and costs'],
        ] as $report)
        <a href="{{ route($report['route']) }}" class="bg-white rounded-lg p-6 shadow-md border border-neutral-100 hover:shadow-lg transition-shadow block">
            <div class="flex items-center mb-3">
                <i data-lucide="{{ $report['icon'] }}" class="w-6 h-6 text-blue-600 mr-3"></i>
                <h3 class="font-semibold text-neutral-900">{{ $report['title'] }}</h3>
            </div>
            <p class="text-sm text-neutral-600">{{ $report['desc'] }}</p>
        </a>
        @endforeach

        <!-- Export -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <div class="flex items-center mb-3">
                <i data-lucide="download" class="w-6 h-6 text-green-600 mr-3"></i>
                <h3 class="font-semibold text-neutral-900">Export Data</h3>
            </div>
            <form method="POST" action="{{ route('reports.export') }}" class="space-y-3">
                @csrf
                <select name="report_type" class="form-input w-full text-sm">
                    <option value="children">Children</option>
                    <option value="donations">Donations</option>
                    <option value="staff">Staff</option>
                    <option value="facilities">Facilities</option>
                    <option value="maintenance">Maintenance</option>
                </select>
                <select name="format" class="form-input w-full text-sm">
                    <option value="csv">CSV</option>
                    <option value="json">JSON</option>
                </select>
                <button type="submit" class="btn btn-primary w-full text-sm">Download</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
