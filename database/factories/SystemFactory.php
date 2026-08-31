<?php

namespace Database\Factories;

use App\Models\System;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<System>
 */
class SystemFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(8),
            'description' => fake()->paragraphs(3, true),
            'main_image' => null,
            'price_from' => fake()->randomFloat(2, 10000, 200000),
            'installation_price' => fake()->randomFloat(2, 5000, 50000),
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => 0,
            'seo_title' => fake()->sentence(6),
            'seo_description' => fake()->sentence(12),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
