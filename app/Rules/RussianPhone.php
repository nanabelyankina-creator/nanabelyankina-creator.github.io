<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RussianPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Некорректный формат телефона.');
            return;
        }

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) === 10 && in_array($digits[0], ['9'], true)) {
            return;
        }
        if (strlen($digits) === 11 && str_starts_with($digits, '7') && in_array($digits[1], ['9'], true)) {
            return;
        }

        $fail('Укажите телефон в формате России: +7 (XXX) XXX-XX-XX или 9XXXXXXXXX.');
    }
}
