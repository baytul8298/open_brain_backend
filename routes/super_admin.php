<?php

use App\Http\Controllers\Admin\ManagePermissionsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Admin\ClassNameController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:super-admin'])->group(function () {
    // Static view pages
    Route::get('/analytics', fn() => inertia('Admin/Analytics/Index'))->name('analytics');
    Route::get('/teachers', fn() => inertia('Admin/Teachers/Index'))->name('teachers');
    Route::get('/courses', fn() => inertia('Admin/Courses/Index'))->name('courses');
    Route::get('/enrollments', fn() => inertia('Admin/Enrollments/Index'))->name('enrollments');
    Route::get('/assignments', fn() => inertia('Admin/Assignments/Index'))->name('assignments');
    Route::get('/live-sessions', fn() => inertia('Admin/LiveSessions/Index'))->name('live-sessions');
    Route::get('/reviews', fn() => inertia('Admin/Reviews/Index'))->name('reviews');
    // Subjects Routes
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::put('/{id}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubjectController::class, 'destroy'])->name('destroy');
    });
    Route::get('/payments', fn() => inertia('Admin/Payments/Index'))->name('payments');
    Route::get('/payouts', fn() => inertia('Admin/Payouts/Index'))->name('payouts');
    Route::get('/coupons', fn() => inertia('Admin/Coupons/Index'))->name('coupons');
    Route::get('/moderation', fn() => inertia('Admin/Moderation/Index'))->name('moderation');
    Route::get('/notifications', fn() => inertia('Admin/Notifications/Index'))->name('notifications');
    Route::get('/settings', fn() => inertia('Admin/Settings/Index'))->name('settings');
    Route::get('/feature-flags', fn() => inertia('Admin/FeatureFlags/Index'))->name('feature-flags');
    Route::get('/audit-log', fn() => inertia('Admin/AuditLog/Index'))->name('audit-log');

    // Roles Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/all', [RoleController::class, 'all'])->name('all');
    });

    // Users Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/all', [UserController::class, 'all'])->name('all');
    });

    // Roles Routes
    Route::prefix('class-name')->name('roles.')->group(function () {
        Route::get('/', [ClassNameController::class, 'index'])->name('index');
        Route::post('/', [ClassNameController::class, 'store'])->name('store');
        Route::put('/{id}', [ClassNameController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClassNameController::class, 'destroy'])->name('destroy');
        Route::get('/all', [ClassNameController::class, 'all'])->name('all');
    });

    // Manage Permissions
    Route::get('/manage-permissions', [ManagePermissionsController::class, 'index'])->name('manage-permissions.index');
    Route::get('/manage-permissions/tree', [ManagePermissionsController::class, 'permissionTree'])->name('manage-permissions.tree');
    Route::post('/manage-permissions/sync-role', [ManagePermissionsController::class, 'syncRole'])->name('manage-permissions.sync-role');
    Route::post('/manage-permissions/sync-user', [ManagePermissionsController::class, 'syncUser'])->name('manage-permissions.sync-user');
});
