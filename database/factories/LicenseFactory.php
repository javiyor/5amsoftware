<?php

namespace Database\Factories;

use App\Enums\LicenseStatus;
use App\Models\CustomerSystem;
use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_system_id' => CustomerSystem::factory(),
            'license_key' => strtoupper(sprintf(
                '%s-%s-%s-%s',
                Str::random(4),
                Str::random(4),
                Str::random(4),
                Str::random(4),
            )),
            'api_token_hash' => null,
            'status' => LicenseStatus::Active,
            'activated_at' => now(),
            'expires_at' => now()->addDays(30),
            'last_check_at' => null,
            'last_ip' => null,
            'domain' => null,
            'installation_id' => null,
            'metadata' => null,
        ];
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LicenseStatus::Suspended,
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LicenseStatus::Revoked,
        ]);
    }
}
