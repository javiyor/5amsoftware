<?php

namespace Database\Factories;

use App\Enums\InstallmentStatus;
use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\Installment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Installment>
 */
class InstallmentFactory extends Factory
{
    public function definition(): array
    {
        $periodStart = now()->startOfMonth()->subMonth();

        return [
            'customer_system_id' => CustomerSystem::factory(),
            'customer_id' => Customer::factory(),
            'number' => 1,
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodStart->copy()->addDays(30)->toDateString(),
            'issued_at' => $periodStart,
            'due_at' => $periodStart->copy()->addDays(30),
            'subtotal' => fake()->randomFloat(2, 10000, 100000),
            'discount_amount' => 0,
            'interest_amount' => 0,
            'total' => fn (array $attributes) => $attributes['subtotal'],
            'status' => InstallmentStatus::Pending,
            'paid_amount' => 0,
            'paid_at' => null,
            'notes' => null,
        ];
    }

    public function overdue(int $days = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InstallmentStatus::Overdue,
            'due_at' => now()->subDays($days),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InstallmentStatus::Paid,
            'paid_amount' => $attributes['total'],
            'paid_at' => now(),
        ]);
    }
}
