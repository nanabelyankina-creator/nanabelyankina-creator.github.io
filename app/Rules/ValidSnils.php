<?php

namespace App\Rules;

use App\Services\SnilsValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSnils implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Поле :attribute должно быть строкой.');
            return;
        }

        $digits = SnilsValidator::normalize($value);

        if (strlen($digits) !== 11) {
            $fail('СНИЛС должен содержать 11 цифр.');
            return;
        }

        if (!SnilsValidator::isValid($value)) {
            $fail('Неверная контрольная сумма СНИЛС.');
        }
    }
}
