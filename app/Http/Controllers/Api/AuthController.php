<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'message'      => 'Cradentials Not Matching!.',
                'requires_otp' => false,
            ], 401);
        }

        if (! $user->phone_verified) {
            return response()->json([
                'message'      => 'Please verify your phone number before logging in.',
                'requires_otp' => true,
                'phone'        => $user->phone,
            ], 403);
        }

        $token = $user->createToken('nuxt-spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->userPayload($user),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'required|string|max:20|unique:users,phone',
            'password'   => 'required|string|min:8',
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'email'          => $request->email,
                'phone'          => $request->phone,
                'password_hash'  => Hash::make($request->password),
                'role'           => 'student',
                'status'         => 'pending_verification',
                'username'       => mb_strtolower($request->last_name),
                'user_type'      => 'student',
                'language'       => 'en',
                'email_verified' => false,
                'phone_verified' => false,
            ]);

            $user->assignRole('student');

            $user->profile()->create([
                'id'           => $user->id,
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'display_name' => Str::title($request->first_name),
                'language'     => 'en',
                'contact_no'   => $request->phone,
            ]);

            $user->studentProfile()->create([
                'user_id' => $user->id,
            ]);

            return $user;
        });

        $otp = $this->generateAndStoreOtp($user, 'registration');

        // TODO: Replace with real SMS service (e.g. Twilio, SSL Commerz SMS)
        Log::info("OTP registration code for {$user->phone}: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please verify your phone number.',
            'phone'   => $request->phone,
        ], 201);
    }

    public function registerTeacher(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:160',
            'email'     => 'required|email|max:255|unique:users,email',
            'phone'     => 'required|string|max:20|unique:users,phone',
            'password'  => 'required|string|min:8',
        ]);

        $nameParts  = explode(' ', trim($request->full_name), 2);
        $firstName  = $nameParts[0];
        $lastName   = $nameParts[1] ?? '';

        $user = DB::transaction(function () use ($request, $firstName, $lastName) {
            $user = User::create([
                'email'          => $request->email,
                'phone'          => $request->phone,
                'password_hash'  => Hash::make($request->password),
                'role'           => 'teacher',
                'status'         => 'pending_verification',
                'username'       => mb_strtolower($lastName ?: $firstName),
                'user_type'      => 'teacher',
                'language'       => 'en',
                'email_verified' => false,
                'phone_verified' => false,
            ]);

            $user->assignRole('wb-teacher');

            $user->profile()->create([
                'id'           => $user->id,
                'first_name'   => $firstName,
                'last_name'    => $lastName,
                'display_name' => Str::title($firstName),
                'language'     => 'en',
                'contact_no'   => $request->phone,
            ]);

            $user->teacherProfile()->create([
                'user_id' => $user->id,
            ]);

            return $user;
        });

        $otp = $this->generateAndStoreOtp($user, 'registration');

        Log::info("OTP teacher registration code for {$user->phone}: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please verify your phone number.',
            'phone'   => $request->phone,
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string|size:6',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            return response()->json(['message' => 'Phone number not found.'], 404);
        }

        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('type', 'registration')
            //->where('otp_code', hash('sha256', $request->otp))
            ->where('otp_code', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpRecord) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        $otpRecord->update(['is_used' => true]);

        $user->update([
            'phone_verified' => true,
            'status'         => 'active',
        ]);

        $token = $user->createToken('nuxt-spa')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $this->userPayload($user),
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('phone_verified', false)
            ->first();

        if (! $user) {
            return response()->json(['message' => 'Phone number not found or already verified.'], 404);
        }

        // Invalidate all previous unused OTPs for this user
        OtpVerification::where('user_id', $user->id)
            ->where('type', 'registration')
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $otp = $this->generateAndStoreOtp($user, 'registration');

        // TODO: Replace with real SMS service
        Log::info("OTP resend code for {$user->phone}: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('profile');

        return response()->json([
            ...$this->userPayload($user),
            'profile' => $user->profile,
        ]);
    }

    private function generateAndStoreOtp(User $user, string $type): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::create([
            'user_id'    => $user->id,
            'phone'      => $user->phone,
            //'otp_code'   => hash('sha256', $otp),
            'otp_code'   => $otp,
            'type'       => $type,
            'is_used'    => false,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
        ]);

        return $otp;
    }

    private function userPayload(User $user): array
    {
        return [
            'id'             => $user->id,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'role'           => $user->role,
            'status'         => $user->status,
            'phone_verified' => (bool) $user->phone_verified,
            'email_verified' => (bool) $user->email_verified,
        ];
    }
}
