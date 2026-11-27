<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordInput implements ValidationRule
{
    /** Bcrypt silently truncates beyond 72 bytes; reject ambiguous passwords before hashing. */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }
        if (str_contains($value, "\0") || (config('hashing.driver', 'bcrypt') === 'bcrypt' && strlen($value) > 72)) {
            $fail('api_validation.invalid')->translate(['attribute' => $attribute]);
        }
    }
}
