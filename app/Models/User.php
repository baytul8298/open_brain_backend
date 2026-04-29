<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;   
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids, HasApiTokens;

    // Resolved to auth.users via search_path ("auth" is first in DB_SCHEMA)
    protected $table = 'users';

    // UUID primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        // Core auth.users columns
        'email',
        'phone',
        'password_hash',
        'role',
        'status',
        'email_verified',
        'phone_verified',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'locked_until',
        // Added columns
        'username',
        'user_type',
        'salt',
        'language',
        'remember_token',
        'email_verified_at',
        // Security / 2FA
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'password_changed_at',
        'failed_login_attempts',
    ];

    protected $appends = ['name', 'contact_no', 'address'];

    public function getNameAttribute(): ?string
    {
        return $this->profile?->display_name;
    }

    public function getContactNoAttribute(): ?string
    {
        return $this->phone;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->profile?->address;
    }

    protected $hidden = [
        'password_hash',
        'remember_token',
        'salt',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified'          => 'boolean',
            'phone_verified'          => 'boolean',
            'password_hash'           => 'hashed',
            'role'                    => UserRole::class,
            'status'                  => UserStatus::class,
            'email_verified_at'       => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password_changed_at'     => 'datetime',
            'locked_until'            => 'datetime',
            'last_login_at'           => 'datetime',
        ];
    }

    // ── Laravel Auth: use password_hash column ───────────────────────────────

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // ── MustVerifyEmail: sync email_verified_at & email_verified boolean ─────

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified === true || $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified'    => true,
            'email_verified_at' => now(),
        ])->save();
    }

    // ── Status helpers ───────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === 'active';
    }

    public function isPending(): bool
    {
        return $this->getRawOriginal('status') === 'pending_verification';
    }

    public function isSuspended(): bool
    {
        return $this->getRawOriginal('status') === 'suspended';
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function hasTwoFactor(): bool
    {
        return (bool) $this->two_factor_secret;
    }

    // ── Login tracking ───────────────────────────────────────────────────────

    public function recordFailedLogin(): void
    {
        $attempts = ($this->failed_login_attempts ?? 0) + 1;
        $data     = [
            'failed_login_attempts' => $attempts,
            'login_attempts'        => $attempts,
        ];

        if ($attempts >= config('app.lockout_max_attempts', 5)) {
            $data['locked_until'] = now()->addMinutes(config('app.lockout_minutes', 15));
        }

        $this->update($data);
    }

    public function recordSuccessfulLogin(string $ip): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'login_attempts'        => 0,
            'locked_until'          => null,
            'last_login_at'         => now(),
            'last_login_ip'         => $ip,
        ]);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function profile()
    {
        return $this->hasOne(\App\Models\Core\Profile::class, 'id', 'id');
    }

    public function studentProfile()
    {
        return $this->hasOne(\App\Models\Core\StudentProfile::class, 'user_id', 'id');
    }

    public function teacherProfile()
    {
        return $this->hasOne(\App\Models\Core\TeacherProfile::class, 'user_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\Learn\Course::class, 'teacher_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Commerce\Enrollment::class, 'student_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(\App\Models\Commerce\TeacherWallet::class, 'teacher_id', 'id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'id');
    }

}
