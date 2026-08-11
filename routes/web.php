<?php

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
    Route::post('/profile/demographic', [ProfileController::class, 'storeDemographic'])->name('profile.demographic.store');
    Route::put('/profile/demographic', [ProfileController::class, 'updateDemographic'])->name('profile.demographic.update');
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
});
