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

        // Базовая защита от очевидно "пустых" значений.
        if (substr($digits, 0, 9) === '000000000') {
            return false;
        }

        $number = (int) substr($digits, 0, 9);
        $control = (int) substr($digits, 9, 2);
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $digits[$i] * (9 - $i);
        }

        // По правилам РФ контрольное число для номеров <= 001-001-998 не рассчитывается.
        if ($number <= 1001998) {
            return true;
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
