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
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
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
    Route::get('/dashboardoms2', [DashboardController::class, 'index']);
    
    // API routes for dashboard
    Route::get('/api/analytics', [DashboardController::class, 'getAnalytics'])->name('analytics');
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.api');
    
    // Children Management
    Route::resource('children', ChildController::class);
    Route::post('children/{child}/assign-talent', [ChildController::class, 'assignTalent'])->name('children.assign-talent');
     Route::post('children/{child}/education-record', [ChildController::class, 'addEducationRecord'])->name('children.education-record');
    Route::post('children/{child}/record-milestone', [ChildController::class, 'recordMilestone'])->name('children.record-milestone');
    Route::post('children/{child}/update-measurements', [ChildController::class, 'updateMeasurements'])->name('children.update-measurements');
    Route::get('children/{child}/profile', [ChildController::class, 'profile'])->name('children.profile');
    
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
    
    // Donors Management
    Route::resource('donors', DonorController::class);
    Route::post('donors/{donor}/add-donation', [DonorController::class, 'addDonation'])->name('donors.add-donation');
    Route::get('donors/{donor}/donation-history', [DonorController::class, 'donationHistory'])->name('donors.donation-history');
    
    // Facilities Management
    Route::resource('facilities', FacilityController::class);
    Route::get('facilities/{facility}/rooms', [FacilityController::class, 'rooms'])->name('facilities.rooms');
    Route::get('facilities/{facility}/list', [FacilityController::class, 'index'])->name('facilities.index');
    Route::post('facilities/{facility}/store', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('facilities/{facility}/show', [FacilityController::class, 'show'])->name('facilities.show');
    Route::get('facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('facilities/{facility}/assign-room', [FacilityController::class, 'assignRoom'])->name('facilities.assign-room');
    
    // Maintenance Management
    Route::resource('maintenance', MaintenanceRequestController::class);
    Route::post('maintenance/{maintenance}/update-status', [MaintenanceRequestController::class, 'updateStatus'])->name('maintenance.update-status');
    Route::post('maintenance/{maintenance}/assign', [MaintenanceRequestController::class, 'assign'])->name('maintenance.assign');
    
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
        
        // PDF Export routes
        Route::get('reports/children/{child}/pdf', [\App\Http\Controllers\ReportController::class, 'exportChildProfile'])->name('reports.child-profile-pdf');
        Route::get('reports/donations/{donor}/pdf', [\App\Http\Controllers\ReportController::class, 'exportDonorReport'])->name('reports.donor-pdf');
    });
           /**

    // Settings (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
        Route::put('settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system');
        Route::post('settings/backup', [SettingsController::class, 'createBackup'])->name('settings.backup');
    });

    */
  
    // Profile Management
    Route::get('profile', [UserController::class, 'show'])->name('profile.show');
    Route::put('profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::put('profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');

  
    
    // API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('children/search', [ChildController::class, 'search'])->name('children.search');
        Route::get('volunteers/search', [VolunteerController::class, 'search'])->name('volunteers.search');
        Route::get('donors/search', [DonorController::class, 'search'])->name('donors.search');
        Route::get('facilities/search', [FacilityController::class, 'search'])->name('facilities.search');
        Route::get('maintenance/stats', [MaintenanceRequestController::class, 'getStats'])->name('maintenance.stats');
        Route::get('donations/stats', [DonorController::class, 'getStats'])->name('donations.stats');
        Route::get('dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    });
});

// Error pages
Route::view('unauthorized', 'errors.unauthorized')->name('unauthorized');
Route::view('404', 'errors.404')->name('404');
Route::view('500', 'errors.500')->name('500');
Route::view('/','welcome')->name('home');

