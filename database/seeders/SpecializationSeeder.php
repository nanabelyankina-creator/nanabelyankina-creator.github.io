<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Анестезиолог',
            'Венеролог',
            'Гастроэнтеролог',
            'Гинеколог',
            'Дерматолог',
            'Детский невролог',
            'Детский офтальмолог',
            'Детский педиатр',
            'Детский уролог',
            'Детский хирург',
            'Кардиолог',
            'Косметолог',
            'Невролог',
            'Нейрохирург',
            'Офтальмолог',
            'Педиатр',
            'Психолог',
            'Ревматолог',
            'Терапевт',
            'Травматолог',
            'Уролог',
            'Хирург',
            'Эндокринолог',
        ];

        foreach ($names as $name) {
            Specialization::firstOrCreate(
                ['name' => $name],
                ['description' => null]
            );
        }
    }
}
