<?php

use App\Http\Controllers\ChildController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//Routes for functions directly related to the Children Model and Controllers
Route::middleware('auth')->group(function () {
    Route::get('/children/index',[ChildController::class, 'index'])->name('list_children');
    Route::get('/children/view_child',[ChildController::class, 'viewchild'])->name('view_child');
    Route::get('/children/register_new',[ChildController::class, 'registernew'])->name('register_newchild');
    Route::post('/children/addbasic_info',[ChildController::class, 'addbasicinfo'])->name('addbasicinfo');
    Route::post('/children/addeducation_info',[ChildController::class, 'addeducationinfo'])->name('addeduinfo');
    Route::post('/children/addmedical_info',[ChildController::class, 'addmedicalinfo'])->name('addmedinfo');
    Route::post('/children/addhousing_info',[ChildController::class, 'addlivinginfo'])->name('addaccoinfo');
    Route::post('/children/addbackground_info',[ChildController::class, 'addbkgrdinfo'])->name('addbckinfo');
    Route::post('/children/adddevelopment_info',[ChildController::class, 'addmiscinfo'])->name('adddevinfo');
     Route::get('/children/delete',[ChildController::class, 'deleteprofileinfo'])->name('delinfo');
});


//Routes for functions directly related to the Staff Model and Controllers
Route::middleware('auth')->group(function () {
    Route::get('/staff/index',[StaffController::class, 'index'])->name('list_staff');
    Route::get('/staff/register_new',[StaffController::class, 'registernew'])->name('register_newstaff');
    Route::get('/staff/staff_details',[StaffController::class, 'viewstaff'])->name('view_staff');
    Route::post('/staff/save_staff',[StaffController::class, 'savestaff'])->name('savestaff');
});
//Routes for functions directly related to the Country Model and Controllers
Route::middleware('auth')->group(function () {
    Route::post('/countries/import',[CountryController::class, 'importcountries'])->name('importcountry');
    Route::get('/countries/list',[CountryController::class, 'index'])->name('listcountry');
});

//Routes for functions directly related to the Room Model and Controllers
Route::middleware('auth')->group(function () {
    Route::get('/room/room_details',[RoomController::class, 'viewroom'])->name('view_room');
    Route::get('/room/room_list',[RoomController::class, 'index'])->name('list_rooms');
});



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
