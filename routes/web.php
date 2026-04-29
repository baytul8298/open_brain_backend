<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified', 'active', 'role:super-admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Grade Levels
    Route::prefix('grade-level')->name('grade-levels.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\GradeLevelController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\GradeLevelController::class, 'store'])->name('store');
        Route::put('/{id}', [App\Http\Controllers\Admin\GradeLevelController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\GradeLevelController::class, 'destroy'])->name('destroy');
    });

    // Class Names
    Route::get('/class-name', [App\Http\Controllers\Admin\ClassNameController::class, 'index'])->name('class-names.index');
    Route::post('/class-name', [App\Http\Controllers\Admin\ClassNameController::class, 'store'])->name('class-names.store');
    Route::put('/class-name/{id}', [App\Http\Controllers\Admin\ClassNameController::class, 'update'])->name('class-names.update');
    Route::delete('/class-name/{id}', [App\Http\Controllers\Admin\ClassNameController::class, 'destroy'])->name('class-names.destroy');
});

Route::middleware(['auth', 'verified', 'active', 'role:super-admin'])->group(function () {
    // Menu Manager
    Route::get('/menus', [App\Http\Controllers\Admin\MenuController::class, 'index'])->name('menus.index');

    // Parent Menu CRUD (no standalone page — managed inside /menus)
    Route::get('/parent-menus', fn () => redirect('/menus'))->name('parent-menus.index');
    Route::post('/parent-menus', [App\Http\Controllers\Admin\MenuController::class, 'storeParent'])->name('parent-menus.store');
    Route::put('/parent-menus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'updateParent'])->name('parent-menus.update');
    Route::delete('/parent-menus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'destroyParent'])->name('parent-menus.destroy');
    Route::post('/parent-menus/reorder-batch', [App\Http\Controllers\Admin\MenuController::class, 'reorderParentBatch'])->name('parent-menus.reorder-batch');

    // Menu CRUD
    Route::post('/menus', [App\Http\Controllers\Admin\MenuController::class, 'storeMenu'])->name('menus.store');
    Route::put('/menus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'updateMenu'])->name('menus.update');
    Route::delete('/menus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'destroyMenu'])->name('menus.destroy');
    Route::post('/menus/reorder-batch', [App\Http\Controllers\Admin\MenuController::class, 'reorderMenuBatch'])->name('menus.reorder-batch');

    // Submenu CRUD
    Route::get('/menus/{menuId}/submenus', [App\Http\Controllers\Admin\MenuController::class, 'getSubmenus'])->name('menus.submenus');
    Route::post('/submenus', [App\Http\Controllers\Admin\MenuController::class, 'storeSubmenu'])->name('submenus.store');
    Route::put('/submenus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'updateSubmenu'])->name('submenus.update');
    Route::delete('/submenus/{id}', [App\Http\Controllers\Admin\MenuController::class, 'destroySubmenu'])->name('submenus.destroy');
    Route::post('/submenus/reorder-batch', [App\Http\Controllers\Admin\MenuController::class, 'reorderSubmenuBatch'])->name('submenus.reorder-batch');

    // Button CRUD
    Route::get('/submenus/{submenuId}/buttons', [App\Http\Controllers\Admin\MenuController::class, 'getButtons'])->name('submenus.buttons');
    Route::post('/buttons', [App\Http\Controllers\Admin\MenuController::class, 'storeButton'])->name('buttons.store');
    Route::put('/buttons/{id}', [App\Http\Controllers\Admin\MenuController::class, 'updateButton'])->name('buttons.update');
    Route::delete('/buttons/{id}', [App\Http\Controllers\Admin\MenuController::class, 'destroyButton'])->name('buttons.destroy');
    Route::post('/buttons/reorder-batch', [App\Http\Controllers\Admin\MenuController::class, 'reorderButtonBatch'])->name('buttons.reorder-batch');
});

require __DIR__.'/auth.php';
