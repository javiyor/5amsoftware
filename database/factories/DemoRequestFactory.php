<?php

namespace Database\Factories;

use App\Enums\DemoRequestStatus;
use App\Models\DemoRequest;
use App\Models\System;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DemoRequest>
 */
class DemoRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'system_id' => System::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'number_of_users' => fake()->numberBetween(1, 100),
            'preferred_date' => fake()->dateTimeBetween('+1 day', '+2 weeks'),
            'preferred_time' => fake()->time('H:i'),
            'comments' => fake()->sentence(),
            'status' => DemoRequestStatus::New,
            'assigned_to' => null,
            'converted_customer_id' => null,
        ];
    }
}
