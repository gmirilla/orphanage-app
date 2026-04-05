<x-layouts.app :title="__('Dashboard')">
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Dashboard</h2>
            <p class="text-sm text-neutral-500">Welcome back, {{ $user->name }} &mdash; {{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="mt-3 sm:mt-0 flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary text-sm">
                <i data-lucide="file-bar-chart" class="w-4 h-4 mr-1"></i> Reports
            </a>
            <a href="{{ route('children.create') }}" class="btn btn-primary text-sm">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Admit Child
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Urgent Maintenance Alert -->
    @if($metrics['urgent_maintenance'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mt-0.5 shrink-0"></i>
        <div>
            <p class="font-medium text-red-800">{{ $metrics['urgent_maintenance'] }} urgent maintenance request{{ $metrics['urgent_maintenance'] > 1 ? 's' : '' }} need attention</p>
            <a href="{{ route('maintenance.index') }}" class="text-sm text-red-600 underline">View maintenance requests &rarr;</a>
        </div>
    </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Children -->
        <a href="{{ route('children.index') }}" class="bg-white rounded-xl border border-neutral-100 shadow-sm p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i data-lucide="baby" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <p class="text-3xl font-bold text-neutral-900">{{ $metrics['total_children'] }}</p>
            <p class="text-sm text-neutral-500 mt-1">Children in Care</p>
        </a>

        <!-- Staff -->
        <a href="{{ route('staff.index') }}" class="bg-white rounded-xl border border-neutral-100 shadow-sm p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <i data-lucide="users" class="w-5 h-5 text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Staff</span>
            </div>
            <p class="text-3xl font-bold text-neutral-900">{{ $metrics['total_staff'] }}</p>
            <p class="text-sm text-neutral-500 mt-1">Team Members</p>
        </a>

        <!-- Monthly Donations -->
        <a href="{{ route('donors.index') }}" class="bg-white rounded-xl border border-neutral-100 shadow-sm p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <i data-lucide="heart-handshake" class="w-5 h-5 text-green-600"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ now()->format('M') }}</span>
            </div>
            <p class="text-3xl font-bold text-neutral-900">&#8358;{{ number_format($thisMonthDonations, 0) }}</p>
            <p class="text-sm text-neutral-500 mt-1">This Month's Donations</p>
        </a>

        <!-- Maintenance -->
        <a href="{{ route('maintenance.index') }}" class="bg-white rounded-xl border border-neutral-100 shadow-sm p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 {{ $metrics['pending_maintenance'] > 0 ? 'bg-orange-100' : 'bg-neutral-100' }} rounded-lg flex items-center justify-center group-hover:opacity-80 transition-colors">
                    <i data-lucide="wrench" class="w-5 h-5 {{ $metrics['pending_maintenance'] > 0 ? 'text-orange-600' : 'text-neutral-500' }}"></i>
                </div>
                @if($metrics['urgent_maintenance'] > 0)
                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">{{ $metrics['urgent_maintenance'] }} urgent</span>
                @else
                    <span class="text-xs font-medium text-neutral-500 bg-neutral-50 px-2 py-1 rounded-full">Pending</span>
                @endif
            </div>
            <p class="text-3xl font-bold text-neutral-900">{{ $metrics['pending_maintenance'] }}</p>
            <p class="text-sm text-neutral-500 mt-1">Maintenance Requests</p>
        </a>
    </div>

    <!-- Charts Row 1: Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Monthly Admissions Chart -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-neutral-900">Child Admissions</h3>
                    <p class="text-xs text-neutral-500">Last 12 months</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4 text-blue-600"></i>
                </div>
            </div>
            <canvas id="admissionsChart" height="200"></canvas>
        </div>

        <!-- Donation Trends Chart -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-neutral-900">Donation Trends</h3>
                    <p class="text-xs text-neutral-500">Last 12 months (&#8358;)</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-green-600"></i>
                </div>
            </div>
            <canvas id="donationsChart" height="200"></canvas>
        </div>
    </div>

    <!-- Charts Row 2: Distributions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Age Distribution -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-neutral-900">Age Distribution</h3>
                    <p class="text-xs text-neutral-500">Children by age group</p>
                </div>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-purple-600"></i>
                </div>
            </div>
            <canvas id="ageChart" height="220"></canvas>
        </div>

        <!-- Staff by Role -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-neutral-900">Staff by Role</h3>
                    <p class="text-xs text-neutral-500">Current team composition</p>
                </div>
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="donut" class="w-4 h-4 text-indigo-600"></i>
                </div>
            </div>
            <canvas id="staffChart" height="220"></canvas>
        </div>

        <!-- Maintenance by Status -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-neutral-900">Maintenance Status</h3>
                    <p class="text-xs text-neutral-500">All requests breakdown</p>
                </div>
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-orange-600"></i>
                </div>
            </div>
            <canvas id="maintenanceChart" height="220"></canvas>
        </div>
    </div>

    <!-- Bottom Row: Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Admissions -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                <h3 class="font-semibold text-neutral-900">Recent Admissions</h3>
                <a href="{{ route('children.index') }}" class="text-xs text-primary-600 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-neutral-50">
                @forelse($recentChildren as $child)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-neutral-50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm shrink-0">
                        {{ strtoupper(substr($child->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-neutral-900 text-sm truncate">{{ $child->name }}</p>
                        <p class="text-xs text-neutral-500">
                            Admitted {{ $child->admission_date?->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $child->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-neutral-100 text-neutral-500' }}">
                        {{ ucfirst($child->status) }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-neutral-400">No recent admissions</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                <h3 class="font-semibold text-neutral-900">Recent Donations</h3>
                <a href="{{ route('donors.index') }}" class="text-xs text-primary-600 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-neutral-50">
                @forelse($recentDonations as $donation)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-neutral-50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-sm shrink-0">
                        {{ strtoupper(substr($donation->donor?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-neutral-900 text-sm truncate">{{ $donation->donor?->name ?? 'Anonymous' }}</p>
                        <p class="text-xs text-neutral-500">{{ $donation->donation_date?->format('M d, Y') }}</p>
                    </div>
                    <span class="text-sm font-semibold text-green-700">
                        &#8358;{{ number_format($donation->amount, 0) }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-neutral-400">No recent donations</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pending Maintenance List -->
    @if($pendingMaintenance->count() > 0)
    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm">
        <div class="flex items-center justify-between p-5 border-b border-neutral-100">
            <h3 class="font-semibold text-neutral-900">Pending Maintenance Requests</h3>
            <a href="{{ route('maintenance.index') }}" class="text-xs text-primary-600 hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 text-left">
                        <th class="px-5 py-3 font-medium text-neutral-600">Title</th>
                        <th class="px-5 py-3 font-medium text-neutral-600">Facility</th>
                        <th class="px-5 py-3 font-medium text-neutral-600">Priority</th>
                        <th class="px-5 py-3 font-medium text-neutral-600">Reported</th>
                        <th class="px-5 py-3 font-medium text-neutral-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @foreach($pendingMaintenance->take(5) as $request)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-5 py-3 font-medium text-neutral-900">{{ $request->title }}</td>
                        <td class="px-5 py-3 text-neutral-600">{{ $request->facility?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $request->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                   ($request->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                   ($request->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-neutral-100 text-neutral-600')) }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-neutral-500">{{ $request->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('maintenance.view', $request) }}" class="text-primary-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'inherit';
Chart.defaults.color = '#6b7280';

// ── Helpers ────────────────────────────────────────────────────────────────────

// Build a full 12-month label array ending this month
function last12MonthLabels() {
    const labels = [];
    for (let i = 11; i >= 0; i--) {
        const d = new Date();
        d.setDate(1);
        d.setMonth(d.getMonth() - i);
        labels.push(d.toLocaleString('default', { month: 'short', year: '2-digit' }));
    }
    return labels;
}

function last12MonthKeys() {
    const keys = [];
    for (let i = 11; i >= 0; i--) {
        const d = new Date();
        d.setDate(1);
        d.setMonth(d.getMonth() - i);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        keys.push(`${y}-${m}`);
    }
    return keys;
}

const monthLabels = last12MonthLabels();
const monthKeys   = last12MonthKeys();

// ── Data from Laravel ──────────────────────────────────────────────────────────

const rawAdmissions = @json($chartData['admission_trends']);
const rawDonations  = @json($chartData['donation_trends']);
const rawAgeGroups  = @json($chartData['age_groups']);
const rawMaintenance= @json($chartData['maintenance_status']);
const rawStaff      = @json($chartData['staff_by_role']);

// Map month-keyed data onto full 12-month arrays
function mapToMonths(raw, valueKey) {
    const map = {};
    raw.forEach(r => { map[r.month] = r[valueKey]; });
    return monthKeys.map(k => map[k] ?? 0);
}

const admissionData  = mapToMonths(rawAdmissions, 'count');
const donationAmount = mapToMonths(rawDonations,  'total');
const donationCount  = mapToMonths(rawDonations,  'count');

// ── Chart 1: Admissions Line ───────────────────────────────────────────────────
new Chart(document.getElementById('admissionsChart'), {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'New Admissions',
            data: admissionData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#3b82f6',
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// ── Chart 2: Donations Bar ─────────────────────────────────────────────────────
new Chart(document.getElementById('donationsChart'), {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'Amount (₦)',
            data: donationAmount,
            backgroundColor: 'rgba(34,197,94,0.75)',
            borderRadius: 4,
            yAxisID: 'y',
        }, {
            label: '# Donations',
            data: donationCount,
            type: 'line',
            borderColor: '#16a34a',
            backgroundColor: 'transparent',
            borderWidth: 2,
            pointRadius: 3,
            tension: 0.4,
            yAxisID: 'y1',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
        scales: {
            y:  { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => '₦' + v.toLocaleString() } },
            y1: { beginAtZero: true, position: 'right', grid: { display: false } },
            x:  { grid: { display: false } }
        }
    }
});

// ── Chart 3: Age Groups Doughnut ───────────────────────────────────────────────
const ageLabels = rawAgeGroups.map(r => r.age_group);
const ageCounts = rawAgeGroups.map(r => r.count);
new Chart(document.getElementById('ageChart'), {
    type: 'doughnut',
    data: {
        labels: ageLabels.length ? ageLabels : ['No Data'],
        datasets: [{
            data: ageCounts.length ? ageCounts : [1],
            backgroundColor: ['#3b82f6','#8b5cf6','#06b6d4','#f59e0b','#ef4444'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } } }
    }
});

// ── Chart 4: Staff by Role Doughnut ───────────────────────────────────────────
const staffLabels = rawStaff.map(r => r.role.charAt(0).toUpperCase() + r.role.slice(1));
const staffCounts = rawStaff.map(r => r.count);
new Chart(document.getElementById('staffChart'), {
    type: 'doughnut',
    data: {
        labels: staffLabels.length ? staffLabels : ['No Data'],
        datasets: [{
            data: staffCounts.length ? staffCounts : [1],
            backgroundColor: ['#6366f1','#a855f7','#ec4899','#f97316','#14b8a6'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } } }
    }
});

// ── Chart 5: Maintenance Status Bar ──────────────────────────────────────────
const statusColors = {
    pending:     '#f59e0b',
    in_progress: '#3b82f6',
    completed:   '#22c55e',
    cancelled:   '#9ca3af',
    on_hold:     '#f97316',
};
const mLabels = rawMaintenance.map(r => r.status.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()));
const mCounts = rawMaintenance.map(r => r.count);
const mColors = rawMaintenance.map(r => statusColors[r.status] ?? '#9ca3af');
new Chart(document.getElementById('maintenanceChart'), {
    type: 'bar',
    data: {
        labels: mLabels.length ? mLabels : ['No Data'],
        datasets: [{
            data: mCounts.length ? mCounts : [0],
            backgroundColor: mColors,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
            y: { grid: { display: false } }
        }
    }
});
</script>
</x-layouts.app>
