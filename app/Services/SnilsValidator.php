<?php

namespace App\Services;

class SnilsValidator
{
    public static function normalize(string $snils): string
    {
        return preg_replace('/\D/', '', $snils);
    }

    public static function isValid(string $snils): bool
    {
        $digits = self::normalize($snils);

        if (strlen($digits) !== 11) {
            return false;
        }

        $control = (int) substr($digits, 9, 2);
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $digits[$i] * (9 - $i);
        }

        $calculated = $sum < 100
            ? $sum
            : ($sum % 101 === 100 ? 0 : $sum % 101);

        return $control === $calculated;
    }

    public static function format(string $snils): string
    {
        $digits = self::normalize($snils);

        if (strlen($digits) !== 11) {
            return $snils;
        }

        return substr($digits, 0, 3) . '-'
            . substr($digits, 3, 3) . '-'
            . substr($digits, 6, 3) . ' '
            . substr($digits, 9, 2);
    }
}
