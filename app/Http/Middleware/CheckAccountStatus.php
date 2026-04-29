<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Block login for inactive or pending accounts.
 * Status values: 1 = active, 0 = inactive, 2 = pending approval
 */
class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // status is cast as boolean: true = active (1), false = inactive (0)
            // But we also check for pending (2) — cast as boolean it becomes true, so we check raw
            $rawStatus = $user->getRawOriginal('status');

            if ($rawStatus == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
            }

            if ($rawStatus == 2) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is pending approval. You will be notified once approved.']);
            }
        }

        return $next($request);
    }
}
