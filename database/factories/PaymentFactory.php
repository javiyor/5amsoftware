<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'payment_method' => 'manual',
            'amount' => fake()->randomFloat(2, 10000, 100000),
            'currency' => 'ARS',
            'status' => PaymentStatus::Pending,
            'paid_at' => null,
            'provider' => null,
            'provider_payment_id' => null,
            'provider_preference_id' => null,
            'provider_external_reference' => null,
            'raw_response' => null,
            'notes' => null,
            'created_by' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Approved,
            'paid_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Rejected,
        ]);
    }
}
