<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\Payment;

test('asigna código de cliente CLI con padding de 6 dígitos', function () {
    $customer = Customer::factory()->create();

    expect($customer->code)->toMatch('/^CLI-\d{6}$/');
});

test('los códigos de cliente son secuenciales y únicos', function () {
    $first = Customer::factory()->create();
    $second = Customer::factory()->create();
    $third = Customer::factory()->create();

    expect($first->code)->toBe('CLI-000001')
        ->and($second->code)->toBe('CLI-000002')
        ->and($third->code)->toBe('CLI-000003')
        ->and($first->code)->not->toBe($second->code);
});

test('respetar el código de cliente provisto manualmente', function () {
    $customer = Customer::factory()->create(['code' => 'CLI-000999']);

    expect($customer->code)->toBe('CLI-000999');
});

test('asigna número de contrato CTR con padding de 6 dígitos', function () {
    $contract = CustomerSystem::factory()->create();

    expect($contract->contract_number)->toMatch('/^CTR-\d{6}$/');
});

test('asigna número de pago PAG con padding de 6 dígitos', function () {
    $payment = Payment::factory()->create();

    expect($payment->payment_number)->toMatch('/^PAG-\d{6}$/');
});
