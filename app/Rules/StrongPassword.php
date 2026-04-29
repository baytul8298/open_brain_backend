<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a password against the policy defined in config/security.php.
 * Use in validation: ['password' => ['required', 'confirmed', new StrongPassword]]
 */
class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $policy = config('security.password');
        $min    = $policy['min_length'];

        if (strlen($value) < $min) {
            $fail("The password must be at least {$min} characters.");
            return;
        }

        if ($policy['require_upper'] && ! preg_match('/[A-Z]/', $value)) {
            $fail('The password must contain at least one uppercase letter.');
            return;
        }

        if ($policy['require_lower'] && ! preg_match('/[a-z]/', $value)) {
            $fail('The password must contain at least one lowercase letter.');
            return;
        }

        if ($policy['require_number'] && ! preg_match('/[0-9]/', $value)) {
            $fail('The password must contain at least one number.');
            return;
        }

        if ($policy['require_symbol'] && ! preg_match('/[\W_]/', $value)) {
            $fail('The password must contain at least one special character (e.g. !@#$%^&*).');
        }
    }
}
