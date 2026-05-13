<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomAllocationController;
use App\Http\Controllers\StaffReportController;
use App\Http\Controllers\RequisitionController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboardoms', [DashboardController::class, 'index'])->name('dashboardoms');

    // API routes for dashboard
    Route::get('/api/analytics', [DashboardController::class, 'getAnalytics'])->name('analytics');
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.api');

    // Children Management (admin + head roles only)
    Route::middleware('role:admin,head_of_schools,head_of_homes,head_of_operations')->group(function () {
        Route::resource('children', ChildController::class);
        Route::post('children/{child}/assign-talent', [ChildController::class, 'assignTalent'])->name('children.assign-talent');
        Route::post('children/{child}/education-record', [ChildController::class, 'addEducationRecord'])->name('children.education-record');
        Route::post('children/{child}/record-milestone', [ChildController::class, 'recordMilestone'])->name('children.record-milestone');
        Route::post('children/{child}/addmilestone', [ChildController::class, 'addMilestone'])->name('children.addmilestone');
        Route::post('children/{child}/update-measurements', [ChildController::class, 'updateMeasurements'])->name('children.update-measurements');
        Route::get('children/{child}/profile', [ChildController::class, 'profile'])->name('children.profile');
    });

    // Staff Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('staff', StaffController::class);
        Route::post('staff/{staff}/schedule-shift', [StaffController::class, 'scheduleShift'])->name('staff.schedule-shift');
        Route::get('staff/{staff}/shifts', [StaffController::class, 'showShifts'])->name('staff.shifts');
    });

    // Volunteers Management
    Route::resource('volunteers', VolunteerController::class);
    Route::post('volunteers/{volunteer}/approve', [VolunteerController::class, 'approve'])->name('volunteers.approve');
    Route::post('volunteers/{volunteer}/assign-task', [VolunteerController::class, 'assignTask'])->name('volunteers.assign-task');

    // Circle of Friends Management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('donors', DonorController::class);
        Route::post('donors/{donor}/add-donation', [DonorController::class, 'addDonation'])->name('donors.add-donation');
        Route::get('donors/{donor}/donation-history', [DonorController::class, 'donationHistory'])->name('donors.donation-history');
    });

    // Facilities Management
    Route::resource('facilities', FacilityController::class);
    Route::get('facilities/{facility}/rooms', [FacilityController::class, 'rooms'])->name('facilities.rooms');
    Route::get('facilities/{facility}/rooms-json', [FacilityController::class, 'roomsJson'])->name('facilities.rooms-json');
    Route::post('facilities/{facility}/assign-room', [FacilityController::class, 'assignRoom'])->name('facilities.assign-room');

    // Room Allocations Management
    Route::get('rooms', [RoomAllocationController::class, 'index'])->name('rooms.index');
    Route::get('rooms/create', [RoomAllocationController::class, 'create'])->name('rooms.create');
    Route::post('rooms', [RoomAllocationController::class, 'store'])->name('rooms.store');
    Route::get('rooms/{roomAllocation}', [RoomAllocationController::class, 'show'])->name('rooms.view');
    Route::get('rooms/{roomAllocation}/edit', [RoomAllocationController::class, 'edit'])->name('rooms.edit');
    Route::put('rooms/{roomAllocation}', [RoomAllocationController::class, 'update'])->name('rooms.update');
    Route::delete('rooms/{roomAllocation}', [RoomAllocationController::class, 'destroy'])->name('rooms.destroy');
    Route::post('rooms/{roomAllocation}/assign-child', [RoomAllocationController::class, 'assignChild'])->name('rooms.assignChild');
    Route::post('rooms/unassign-child', [RoomAllocationController::class, 'unassignChild'])->name('rooms.unassignChild');

    // Maintenance Management
    Route::get('maintenance', [MaintenanceRequestController::class, 'index'])->name('maintenance.index');
    Route::get('maintenance/create', [MaintenanceRequestController::class, 'create'])->name('maintenance.create');
    Route::post('maintenance', [MaintenanceRequestController::class, 'store'])->name('maintenance.store');
    Route::get('maintenance/{maintenanceRequest}', [MaintenanceRequestController::class, 'view'])->name('maintenance.view');
    Route::get('maintenance/{maintenanceRequest}/edit', [MaintenanceRequestController::class, 'editRequest'])->name('maintenance.edit_request');
    Route::post('maintenance/{maintenanceRequest}/update-status', [MaintenanceRequestController::class, 'updateStatus'])->name('maintenance.update_status');
    Route::post('maintenance/{maintenanceRequest}/assign', [MaintenanceRequestController::class, 'assign'])->name('maintenance.assign');
    Route::delete('maintenance/{maintenanceRequest}', [MaintenanceRequestController::class, 'destroy'])->name('maintenance.destroy');
    Route::post('maintenance/new', [MaintenanceRequestController::class, 'newRequest'])->name('maintenance.new');

    // Requisitions
    Route::prefix('requisitions')->name('requisitions.')->group(function () {
        Route::get('/',                      [RequisitionController::class, 'index'])->name('index');
        Route::get('/create',                [RequisitionController::class, 'create'])->name('create');
        Route::post('/',                     [RequisitionController::class, 'store'])->name('store');
        Route::get('/{requisition}',         [RequisitionController::class, 'show'])->name('show');
        Route::get('/{requisition}/edit',    [RequisitionController::class, 'edit'])->name('edit');
        Route::put('/{requisition}',         [RequisitionController::class, 'update'])->name('update');
        Route::post('/{requisition}/submit', [RequisitionController::class, 'submit'])->name('submit');
        Route::delete('/{requisition}',      [RequisitionController::class, 'destroy'])->name('destroy');
        Route::get('/{requisition}/documents/{document}/download',
            [RequisitionController::class, 'downloadDocument'])->name('documents.download');
        Route::middleware('role:admin,head_of_operations')->group(function () {
            Route::post('/{requisition}/approve', [RequisitionController::class, 'approve'])->name('approve');
            Route::post('/{requisition}/reject',  [RequisitionController::class, 'reject'])->name('reject');
        });
    });

    // Staff Reports
    Route::prefix('staff-reports')->name('staff-reports.')->group(function () {
        Route::get('/',                  [StaffReportController::class, 'index'])->name('index');
        Route::get('/create',            [StaffReportController::class, 'create'])->name('create');
        Route::post('/',                 [StaffReportController::class, 'store'])->name('store');
        Route::get('/{staffReport}',     [StaffReportController::class, 'show'])->name('show');
        Route::get('/{staffReport}/edit',[StaffReportController::class, 'edit'])->name('edit');
        Route::put('/{staffReport}',     [StaffReportController::class, 'update'])->name('update');
        Route::post('/{staffReport}/submit',   [StaffReportController::class, 'submit'])->name('submit');
        Route::get('/{staffReport}/download',  [StaffReportController::class, 'download'])->name('download');
        Route::delete('/{staffReport}',        [StaffReportController::class, 'destroy'])->name('destroy');
        Route::middleware('role:admin,head_of_operations')->group(function () {
            Route::post('/{staffReport}/approve', [StaffReportController::class, 'approve'])->name('approve');
            Route::post('/{staffReport}/reject',  [StaffReportController::class, 'reject'])->name('reject');
        });
    });

    // Documents Management
    Route::resource('documents', DocumentController::class);
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Reports (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/children', [ReportController::class, 'childrenReport'])->name('reports.children');
        Route::get('reports/donations', [ReportController::class, 'donationsReport'])->name('reports.donations');
        Route::get('reports/staff', [ReportController::class, 'staffReport'])->name('reports.staff');
        Route::get('reports/facilities', [ReportController::class, 'facilitiesReport'])->name('reports.facilities');
        Route::get('reports/maintenance', [ReportController::class, 'maintenanceReport'])->name('reports.maintenance');
        Route::post('reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('reports/children/{child}/pdf', [ReportController::class, 'exportChildProfile'])->name('reports.child-profile-pdf');
        Route::get('reports/donations/{donor}/pdf', [ReportController::class, 'exportDonorReport'])->name('reports.donor-pdf');
    });

    // Profile Management
    Route::get('profile', [UserController::class, 'show'])->name('profile.show');
    Route::put('profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::put('profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');

    // API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::middleware('role:admin,head_of_schools,head_of_homes,head_of_operations')->group(function () {
            Route::get('children/search', [ChildController::class, 'search'])->name('children.search');
            Route::get('children/stats', [ChildController::class, 'getStatistics'])->name('children.stats');
        });
        Route::middleware('role:admin')->group(function () {
            Route::get('donors/search', [DonorController::class, 'search'])->name('donors.search');
            Route::get('donors/stats', [DonorController::class, 'getStats'])->name('donations.stats');
        });
        Route::get('volunteers/search', [VolunteerController::class, 'search'])->name('volunteers.search');
        Route::get('facilities/search', [FacilityController::class, 'search'])->name('facilities.search');
        Route::get('maintenance/stats', [MaintenanceRequestController::class, 'getStats'])->name('maintenance.stats');
        Route::get('dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    });
});

// Error pages
Route::view('unauthorized', 'errors.unauthorized')->name('unauthorized');
Route::view('404', 'errors.404')->name('404');
Route::view('500', 'errors.500')->name('500');
