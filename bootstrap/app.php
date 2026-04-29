<?php

use App\Http\Middleware\AuditLogMiddleware;
use App\Http\Middleware\CheckAccountStatus;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SessionTimeoutMiddleware;
use App\Http\Middleware\TwoFactorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(base_path('routes/super_admin.php'));
            Route::middleware('web')->group(base_path('routes/admin.php'));
            Route::middleware('web')->group(base_path('routes/student.php'));
            Route::middleware('web')->group(base_path('routes/teacher.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ── API middleware ───────────────────────────────────────────────────
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // ── Global web middleware ────────────────────────────────────────────
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            CheckAccountStatus::class,    // blocks inactive/pending accounts on every request
            SessionTimeoutMiddleware::class, // enforces inactivity timeout
            AuditLogMiddleware::class,    // logs mutating actions
        ]);

        // ── Route middleware aliases ─────────────────────────────────────────
        $middleware->alias([
            'role'        => RoleMiddleware::class,
            'permission'  => PermissionMiddleware::class,
            'two-factor'  => TwoFactorMiddleware::class,
            'active'      => CheckAccountStatus::class,
        ]);

        // ── Redirects ────────────────────────────────────────────────────────
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
