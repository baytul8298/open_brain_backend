<?php

use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:wb-teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::get('/students', [TeacherController::class, 'students'])->name('students');
    Route::get('/assignments', [TeacherController::class, 'assignments'])->name('assignments');
    Route::get('/courses', [TeacherController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [TeacherController::class, 'createCourse'])->name('courses.create');
    Route::get('/analytics', [TeacherController::class, 'analytics'])->name('analytics');
});
