<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce session inactivity timeout.
 * Logs out users who have been idle longer than SESSION_TIMEOUT_MINUTES (default: 30).
 */
class SessionTimeoutMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $timeoutMinutes = (int) config('session.timeout_minutes', 30);
        $lastActivity   = $request->session()->get('last_activity_at');

        if ($lastActivity && now()->diffInMinutes($lastActivity) >= $timeoutMinutes) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired due to inactivity.'], 401);
            }

            return redirect()->route('login')
                ->withErrors(['session' => 'Your session expired due to inactivity. Please log in again.']);
        }

        $request->session()->put('last_activity_at', now());

        return $next($request);
    }
}
