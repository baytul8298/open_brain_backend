<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check that the authenticated user has one of the required roles.
 * Uses Spatie Permission.
 *
 * Usage in routes:
 *   ->middleware('role:super-admin')
 *   ->middleware('role:admin|super-admin')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden: insufficient role.'], 403);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
