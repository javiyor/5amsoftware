<?php

namespace Database\Factories;

use App\Enums\CustomerSystemStatus;
use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\System;
use App\Models\SystemPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerSystem>
 */
class CustomerSystemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'system_id' => System::factory(),
            'system_plan_id' => SystemPlan::factory(),
            'custom_name' => null,
            'started_at' => now()->subDays(40),
            'activated_at' => now()->subDays(40),
            'billing_started_at' => now()->subDays(30),
            'next_billing_at' => now(),
            'billing_interval_days' => 30,
            'grace_days' => 10,
            'monthly_amount' => fake()->randomFloat(2, 10000, 100000),
            'setup_amount' => 0,
            'currency' => 'ARS',
            'automatic_billing' => true,
            'automatic_suspension' => true,
            'status' => CustomerSystemStatus::Active,
            'manual_suspension' => false,
            'manual_suspension_reason' => null,
            'grace_override_until' => null,
            'domain' => null,
            'installation_id' => null,
            'notes' => null,
            'cancelled_at' => null,
        ];
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerSystemStatus::Overdue,
            'next_billing_at' => now()->subDays(5),
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerSystemStatus::Suspended,
            'next_billing_at' => now()->subDays(30),
        ]);
    }
}
