<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin нужен до сидинга чата/других связанных данных
        User::updateOrCreate(
            ['email' => 'admin@clinic.local'],
            [
                'name'       => 'Администратор',
                'password'   => Hash::make('admin12345'),
                'role'       => 'admin',
                'is_blocked' => false,
            ]
        );

        $this->call([
            SpecializationSeeder::class,
            ClinicDataSeeder::class,
        ]);
    }
}
