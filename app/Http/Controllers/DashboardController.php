<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Donor;
use App\Models\Donation;
use App\Models\MaintenanceRequest;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Basic metrics
        $metrics = [
            'total_children' => Child::active()->count(),
            'total_staff' => User::byRole('admin')->orWhere('role', 'caregiver')->orWhere('role', 'nurse')->orWhere('role', 'teacher')->count(),
            'total_volunteers' => Volunteer::active()->count(),
            'total_donors' => Donor::active()->count(),
            'active_donors' => Donor::where('status', 'active')->count(),
            'pending_maintenance' => MaintenanceRequest::pending()->count(),
            'urgent_maintenance' => MaintenanceRequest::urgent()->count(),
        ];

        // Recent activities
        $recentChildren = Child::with('admittedBy')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentDonations = Donation::with('donor')
            ->received()
            ->orderBy('donation_date', 'desc')
            ->limit(5)
            ->get();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Charts data
        $chartData = $this->getChartData();

        return view('dashboard', compact(
            'metrics',
            'recentChildren',
            'recentDonations',
            'unreadNotifications',
            'chartData',
            'user'
        ));
    }

    public function getChartData()
    {
        $driver = DB::getDriverName();

        $dateFormat = $driver === 'sqlite'
            ? 'strftime("%Y-%m", admission_date)'
            : 'DATE_FORMAT(admission_date, "%Y-%m")';

        $admissionTrends = Child::selectRaw("
    {$dateFormat} as month,
    COUNT(*) as count
")
            ->where('admission_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Donation trends (last 12 months)
        $dateFormatDonation = $driver === 'sqlite'
            ? 'strftime("%Y-%m", donation_date)'
            : 'DATE_FORMAT(donation_date, "%Y-%m")';
        $donationTrends = Donation::selectRaw("
            {$dateFormatDonation} as month,
            SUM(amount) as total,
            COUNT(*) as count
        ")
            ->where('donation_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->where('status', 'received')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Children by age groups
        if ($driver === 'sqlite') {
            $ageCase = "
        CASE
            WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 0 AND 5 THEN '0-5 years'
            WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 6 AND 10 THEN '6-10 years'
            WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 11 AND 15 THEN '11-15 years'
            WHEN CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER) BETWEEN 16 AND 18 THEN '16-18 years'
            ELSE '18+ years'
        END as age_group
    ";
        } else {
            $ageCase = "
        CASE
            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 5 THEN '0-5 years'
            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 6 AND 10 THEN '6-10 years'
            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 11 AND 15 THEN '11-15 years'
            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 16 AND 18 THEN '16-18 years'
            ELSE '18+ years'
        END as age_group
    ";
        }

        $ageGroups = Child::active()
            ->selectRaw("{$ageCase}, COUNT(*) as count")
            ->groupBy('age_group')
            ->get();


        // Maintenance requests by status
        $maintenanceStatus = MaintenanceRequest::selectRaw('
            status,
            COUNT(*) as count
        ')
            ->groupBy('status')
            ->get();

        // Staff by role
        $staffByRole = User::selectRaw('
            role,
            COUNT(*) as count
        ')
            ->whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher'])
            ->groupBy('role')
            ->get();

        return [
            'admission_trends' => $admissionTrends,
            'donation_trends' => $donationTrends,
            'age_groups' => $ageGroups,
            'maintenance_status' => $maintenanceStatus,
            'staff_by_role' => $staffByRole,
        ];
    }

    public function getAnalytics()
    {
        $user = Auth::user();;

        // Financial analytics
        $thisYearDonations = Donation::received()
            ->whereYear('donation_date', Carbon::now()->year)
            ->sum('amount');

        $lastYearDonations = Donation::received()
            ->whereYear('donation_date', Carbon::now()->subYear()->year)
            ->sum('amount');

        $donationGrowth = $lastYearDonations > 0
            ? (($thisYearDonations - $lastYearDonations) / $lastYearDonations) * 100
            : 0;

        // Children analytics
        $totalChildren = Child::active()->count();
        $newAdmissionsThisMonth = Child::whereMonth('admission_date', Carbon::now()->month)
            ->whereYear('admission_date', Carbon::now()->year)
            ->count();

        $newAdmissionsLastMonth = Child::whereMonth('admission_date', Carbon::now()->subMonth()->month)
            ->whereYear('admission_date', Carbon::now()->subMonth()->year)
            ->count();

        $admissionGrowth = $newAdmissionsLastMonth > 0
            ? (($newAdmissionsThisMonth - $newAdmissionsLastMonth) / $newAdmissionsLastMonth) * 100
            : 0;

        // Occupancy analytics
        $totalBeds = DB::table('room_allocations')
            ->join('facilities', 'room_allocations.facility_id', '=', 'facilities.id')
            ->where('facilities.type', 'dormitory')
            ->where('room_allocations.is_active', true)
            ->sum('room_allocations.bed_count');

        $occupiedBeds = DB::table('room_allocations')
            ->join('facilities', 'room_allocations.facility_id', '=', 'facilities.id')
            ->join('child_room_assignments', 'room_allocations.id', '=', 'child_room_assignments.room_allocation_id')
            ->where('facilities.type', 'dormitory')
            ->where('room_allocations.is_active', true)
            ->whereNull('child_room_assignments.unassigned_date')
            ->distinct('child_room_assignments.child_id')
            ->count('child_room_assignments.child_id');

        $occupancyRate = $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;

        // Recent activity logs
        $recentActivities = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
            ->select('audit_logs.*', 'users.name as user_name')
            ->orderBy('audit_logs.created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'financial' => [
                'this_year_donations' => $thisYearDonations,
                'last_year_donations' => $lastYearDonations,
                'donation_growth' => round($donationGrowth, 2),
            ],
            'children' => [
                'total' => $totalChildren,
                'new_admissions_this_month' => $newAdmissionsThisMonth,
                'admission_growth' => round($admissionGrowth, 2),
            ],
            'occupancy' => [
                'total_beds' => $totalBeds,
                'occupied_beds' => $occupiedBeds,
                'occupancy_rate' => round($occupancyRate, 1),
                'available_beds' => $totalBeds - $occupiedBeds,
            ],
            'recent_activities' => $recentActivities,
        ]);
    }
}
