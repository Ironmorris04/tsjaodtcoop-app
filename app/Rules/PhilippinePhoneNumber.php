<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhilippinePhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if value is null or empty
        if (empty($value)) {
            return;
        }

        // Remove any non-numeric characters
        $cleanValue = preg_replace('/\D/', '', $value);

        // Check if exactly 11 digits
        if (strlen($cleanValue) !== 11) {
            $fail('The :attribute must be exactly 11 digits.');
            return;
        }

        // Check if it starts with 09 (standard Philippine mobile number format)
        if (!str_starts_with($cleanValue, '09')) {
            $fail('The :attribute must start with 09.');
            return;
        }
    }
}
