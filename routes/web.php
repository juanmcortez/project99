<?php

use App\Http\Controllers\ActivityLogs\ActivityLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Profiles\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/demographic', [ProfileController::class, 'storeDemographic'])->name('profile.demographic.store');
    Route::put('/profile/demographic', [ProfileController::class, 'updateDemographic'])->name('profile.demographic.update');
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::post('/profile/phone', [ProfileController::class, 'storePhone'])->name('profile.phone.store');
    Route::put('/profile/phone/{phone}', [ProfileController::class, 'updatePhone'])->name('profile.phone.update');
    Route::delete('/profile/phone/{phone}', [ProfileController::class, 'destroyPhone'])->name('profile.phone.destroy');
});

Route::middleware(['auth', 'verified', 'permission:activity-log.view'])->group(function () {
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/data', [ActivityLogController::class, 'data'])->name('activity-log.data');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('permission:users.manage')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });

    Route::middleware('permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    Route::middleware('permission:permissions.manage')->group(function () {
        Route::resource('permissions', PermissionController::class)->except('show');
    });
});
