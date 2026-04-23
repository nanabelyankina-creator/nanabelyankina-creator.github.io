<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResetRolePasswordsSeeder extends Seeder
{
    public function run(): void
    {
        $rolePasswords = [
            'admin' => 'admin123',
            'doctor' => 'doctor123',
            'patient' => 'patient123',
        ];

        foreach ($rolePasswords as $role => $plain) {
            User::where('role', $role)->update([
                'password' => Hash::make($plain),
            ]);
        }
    }
}

