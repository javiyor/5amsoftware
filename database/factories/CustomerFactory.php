<?php

namespace Database\Factories;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company_name' => fake()->company(),
            'business_name' => fake()->company(),
            'tax_id' => fake()->numerify('##-########-#'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Argentina',
            'postal_code' => fake()->postcode(),
            'status' => CustomerStatus::Lead,
            'notes' => null,
            'registered_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Active,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Suspended,
        ]);
    }
}
