<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // ── Rate limiting: 5 attempts per minute per IP+email ───────────────
        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // ── Attempt authentication ──────────────────────────────────────────
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            
            // Record failed attempt if user exists
            User::where('email', $request->input('email'))
                ->first()
                ?->recordFailedLogin();

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        
        $user = Auth::user();

        // ── Check account-level lock (post-authentication) ────────────────
        if ($user->isLocked()) {
            Auth::logout();
            
            throw ValidationException::withMessages([
                'email' => 'Your account is temporarily locked due to multiple failed login attempts. Please try again later.',
            ]);
        }

        // ── Account status check ────────────────────────────────────────────
        $status = $user->getRawOriginal('status');

        if (in_array($status, ['inactive', 'suspended', 'pending_verification'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $status === 'pending_verification'
                ? 'Your account is pending approval. You will be notified once an administrator approves it.'
                : 'Your account has been deactivated. Please contact support.';

            throw ValidationException::withMessages(['email' => $message]);
        }

        // ── Role validation ─────────────────────────────────────────────────
        if ($user->roles->isEmpty()) {
            Auth::logout();
            
            throw ValidationException::withMessages([
                'email' => 'No role assigned. Please contact support.',
            ]);
        }

        // ── Successful login ────────────────────────────────────────────────
        $request->session()->regenerate();
        $request->session()->put('last_activity_at', now());
        
        $user->recordSuccessfulLogin($request->ip());

        // ── Redirect based on role type ─────────────────────────────────────
        $roleType = $user->roles->first()->role_type ?? null;

        $route = match ($roleType) {
            'admin'   => 'dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            default   => null,
        };

        if ($route === null) {
            Auth::logout();
            
            throw ValidationException::withMessages([
                'email' => 'Invalid role configuration. Please contact support.',
            ]);
        }

        return redirect()->route($route)
            ->with('success', 'Welcome back! You have successfully logged in.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been successfully logged out.');
    }
}
