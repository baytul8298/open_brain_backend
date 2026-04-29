<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasUuids;

    protected $table = 'auth.otp_verifications';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'phone',
        'otp_code',
        'type',
        'is_used',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
