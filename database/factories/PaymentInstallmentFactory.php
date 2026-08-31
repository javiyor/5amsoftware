<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Payment;
use App\Models\PaymentInstallment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentInstallment>
 */
class PaymentInstallmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'installment_id' => Installment::factory(),
            'amount' => fake()->randomFloat(2, 10000, 100000),
        ];
    }
}
