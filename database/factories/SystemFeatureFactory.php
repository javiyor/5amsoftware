<?php

namespace Database\Factories;

use App\Models\System;
use App\Models\SystemFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SystemFeature>
 */
class SystemFeatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'system_id' => System::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'sort_order' => 0,
        ];
    }
}
