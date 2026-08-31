<?php

declare(strict_types=1);

use App\Models\Installment;
use Carbon\Carbon;

test('una cuota no está vencida el día de su vencimiento', function () {
    $installment = Installment::factory()->create(['due_at' => Carbon::create(2026, 8, 1, 12)]);

    expect($installment->daysOverdue(Carbon::create(2026, 8, 1, 12)))->toBe(0)
        ->and($installment->isOverdue(Carbon::create(2026, 8, 1, 12)))->toBeFalse();
});

test('el primer día completo posterior al vencimiento suma un día', function () {
    $installment = Installment::factory()->create(['due_at' => Carbon::create(2026, 8, 1, 12)]);

    expect($installment->daysOverdue(Carbon::create(2026, 8, 2, 12)))->toBe(1);
});

test('tabla de referencia de días de atraso según la regla centralizada', function (string $now, int $expected) {
    $installment = Installment::factory()->create(['due_at' => Carbon::create(2026, 8, 1, 12)]);

    expect($installment->daysOverdue(Carbon::createFromFormat('Y-m-d H:i:s', $now)))->toBe($expected);
})->with([
    ['2026-08-01 12:00:00', 0],
    ['2026-08-02 12:00:00', 1],
    ['2026-08-10 12:00:00', 9],
    ['2026-08-11 12:00:00', 10],
    ['2026-08-12 12:00:00', 11],
]);

test('sin fecha de vencimiento el atraso es cero', function () {
    $installment = Installment::factory()->create(['due_at' => null]);

    expect($installment->daysOverdue())->toBe(0);
});

test('nunca devuelve atraso negativo', function () {
    $installment = Installment::factory()->create(['due_at' => Carbon::create(2026, 8, 1, 12)]);

    expect($installment->daysOverdue(Carbon::create(2026, 7, 1, 12)))->toBe(0);
});

test('una cuota pagada queda saldada', function () {
    $installment = Installment::factory()->paid()->create();

    expect($installment->isFullyPaid())->toBeTrue()
        ->and($installment->isSettled())->toBeTrue();
});

test('saldo restante de la cuota', function () {
    $installment = Installment::factory()->create([
        'total' => 100.00,
        'paid_amount' => 30.00,
    ]);

    expect($installment->remaining())->toBe('70.00');
});
