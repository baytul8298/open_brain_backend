<?php

use App\Http\Controllers\Student\StudentProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    Route::put('/profile/about', [StudentProfileController::class, 'updateAbout'])->name('profile.updateAbout');
    Route::put('/profile/personal-info', [StudentProfileController::class, 'updatePersonalInfo'])->name('profile.updatePersonalInfo');
});
