<?php

namespace Database\Factories;

use App\Models\System;
use App\Models\SystemPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SystemPlan>
 */
class SystemPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'system_id' => System::factory(),
            'name' => ucfirst(fake()->unique()->word()),
            'slug' => fn (array $attributes) => Str::slug((string) $attributes['name']),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10000, 100000),
            'setup_price' => fake()->randomFloat(2, 0, 20000),
            'billing_interval_days' => 30,
            'grace_days' => 10,
            'max_users' => fake()->randomElement([5, 10, 25, 50]),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
