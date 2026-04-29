<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NavMenuController;
use App\Http\Controllers\Api\TeacherProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    // ===== PUBLIC ROUTES =====
    Route::prefix('student')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::prefix('teacher')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'registerTeacher']);
    });

    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);

    // ===== PROTECTED ROUTES =====
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('nav-menus', [NavMenuController::class, 'index']);
    });
});

Route::prefix('teacher')->middleware('auth:sanctum')->group(function () {

    // Profile — full data fetch
    Route::get('profile', [TeacherProfileController::class, 'show']);

    // About (bio + headline)
    Route::put('profile/about', [TeacherProfileController::class, 'updateAbout']);

    // Education & Certificate
    Route::post('profile/education',        [TeacherProfileController::class, 'storeEducation']);
    Route::put('profile/education/{id}',    [TeacherProfileController::class, 'updateEducation']);
    Route::delete('profile/education/{id}', [TeacherProfileController::class, 'destroyEducation']);

    // Teacher Experience
    Route::put('profile/experience', [TeacherProfileController::class, 'updateExperience']);

    // Subjects I Teach
    Route::put('profile/subjects', [TeacherProfileController::class, 'updateSubjects']);

    // Teaching Style
    Route::post('profile/teaching-styles',        [TeacherProfileController::class, 'storeTeachingStyle']);
    Route::put('profile/teaching-styles/{id}',    [TeacherProfileController::class, 'updateTeachingStyle']);
    Route::delete('profile/teaching-styles/{id}', [TeacherProfileController::class, 'destroyTeachingStyle']);

    // Weekly Availability
    Route::put('profile/availability', [TeacherProfileController::class, 'updateAvailability']);

    // Teacher Verification
    Route::post('profile/verification', [TeacherProfileController::class, 'storeVerification']);
});
