<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('CambiarMe_2026!'),
                'email_verified_at' => now(),
                'is_active' => true,
                'must_change_password' => true,
            ],
        );

        $admin->assignRole(UserRole::SuperAdmin);
    }
}
