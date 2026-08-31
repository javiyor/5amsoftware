<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/**
 * Crea un usuario (sean los roles necesarios) con el rol indicado.
 */
function roleUser(array $attributes = [], ?UserRole $role = null): User
{
    if (! Role::query()->exists()) {
        (new RoleSeeder)->run();
    }

    $user = User::factory()->create($attributes);

    if ($role !== null) {
        $user->assignRole($role);
    }

    return $user;
}

function adminUser(array $attributes = []): User
{
    return roleUser($attributes, UserRole::SuperAdmin);
}

function customerUser(array $attributes = []): User
{
    return roleUser($attributes, UserRole::Customer);
}
