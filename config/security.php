<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length'     => (int) env('PASSWORD_MIN_LENGTH', 8),
        'require_upper'  => (bool) env('PASSWORD_REQUIRE_UPPER', true),
        'require_lower'  => (bool) env('PASSWORD_REQUIRE_LOWER', true),
        'require_number' => (bool) env('PASSWORD_REQUIRE_NUMBER', true),
        'require_symbol' => (bool) env('PASSWORD_REQUIRE_SYMBOL', true),
        'max_age_days'   => (int) env('PASSWORD_MAX_AGE_DAYS', 90),   // 0 = never expires
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Lockout
    |--------------------------------------------------------------------------
    */
    'lockout' => [
        'max_attempts'  => (int) env('LOCKOUT_MAX_ATTEMPTS', 5),
        'lockout_minutes' => (int) env('LOCKOUT_MINUTES', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    */
    'two_factor' => [
        'enabled' => (bool) env('TWO_FACTOR_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'enabled'         => (bool) env('AUDIT_LOG_ENABLED', true),
        'retention_days'  => (int) env('AUDIT_LOG_RETENTION_DAYS', 90),
    ],

];
