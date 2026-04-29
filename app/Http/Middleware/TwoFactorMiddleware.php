<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforces Two-Factor Authentication for users who have 2FA enabled.
 * After login, users with 2FA are redirected to the 2FA challenge page
 * until they complete verification for this session.
 *
 * To enable 2FA for a user, set: users.two_factor_secret (non-null)
 * Session flag: 'two_factor_confirmed' = true (set after successful 2FA)
 */
class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // If user has 2FA secret but hasn't confirmed this session
        if (
            $user->two_factor_secret
            && ! $request->session()->get('two_factor_confirmed', false)
            && ! $request->routeIs('two-factor.*')
        ) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Two-factor authentication required.'], 403);
            }

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
