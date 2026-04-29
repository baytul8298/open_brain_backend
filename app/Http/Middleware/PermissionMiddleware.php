<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check that the authenticated user has the required permission.
 * Uses Spatie Permission.
 *
 * Usage in routes:
 *   ->middleware('permission:edit-users')
 *   ->middleware('permission:edit-users|delete-users')
 */
class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        foreach ($permissions as $permission) {
            if ($request->user()->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden: missing permission.'], 403);
        }

        abort(403, 'You do not have the required permission.');
    }
}
