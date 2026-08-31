<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\DemoRequest;
use App\Models\DemoRequestLog;
use App\Models\Installment;
use App\Models\License;
use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\System;
use App\Models\SystemFeature;
use App\Models\SystemPlan;
use Illuminate\Support\Facades\DB;

test('relaciones del contrato (customer_system)', function () {
    $contract = CustomerSystem::factory()->create();

    expect($contract->customer)->toBeInstanceOf(Customer::class)
        ->and($contract->system)->toBeInstanceOf(System::class)
        ->and($contract->plan)->toBeInstanceOf(SystemPlan::class);
});

test('licencia y cuotas del contrato', function () {
    $contract = CustomerSystem::factory()->create();
    $license = License::factory()->create(['customer_system_id' => $contract->id]);
    $installment = Installment::factory()->create([
        'customer_system_id' => $contract->id,
        'customer_id' => $contract->customer_id,
    ]);

    expect($contract->license->is($license))->toBeTrue()
        ->and($contract->installments->first()->is($installment))->toBeTrue()
        ->and($license->customerSystem->is($contract))->toBeTrue();
});

test('relaciones del sistema (features y planes)', function () {
    $system = System::factory()->create();
    $feature = SystemFeature::factory()->create(['system_id' => $system->id]);
    $plan = SystemPlan::factory()->create(['system_id' => $system->id]);

    expect($system->features->first()->is($feature))->toBeTrue()
        ->and($system->plans->first()->is($plan))->toBeTrue()
        ->and($feature->system->is($system))->toBeTrue()
        ->and($plan->system->is($system))->toBeTrue();
});

test('relaciones del cliente', function () {
    $customer = Customer::factory()->create();
    $contract = CustomerSystem::factory()->create(['customer_id' => $customer->id]);
    $installment = Installment::factory()->create([
        'customer_system_id' => $contract->id,
        'customer_id' => $customer->id,
    ]);
    $payment = Payment::factory()->create(['customer_id' => $customer->id]);

    expect($customer->customerSystems->first()->is($contract))->toBeTrue()
        ->and($customer->installments->first()->is($installment))->toBeTrue()
        ->and($customer->payments->first()->is($payment))->toBeTrue();
});

test('relaciones del pago y sus cuotas', function () {
    $customer = Customer::factory()->create();
    $payment = Payment::factory()->create(['customer_id' => $customer->id]);
    $installment = Installment::factory()->create(['customer_id' => $customer->id]);

    $payment->installments()->attach($installment, ['amount' => $installment->total]);

    expect($payment->customer->is($customer))->toBeTrue()
        ->and($payment->installments->first()->is($installment))->toBeTrue()
        ->and((float) $payment->installments->first()->pivot->amount)->toBe((float) $installment->total)
        ->and($installment->payments->first()->is($payment))->toBeTrue();
});

test('imputación de pago crea registro en payment_installments', function () {
    $payment = Payment::factory()->create();
    $installment = Installment::factory()->create();

    PaymentInstallment::factory()->create([
        'payment_id' => $payment->id,
        'installment_id' => $installment->id,
        'amount' => $installment->total,
    ]);

    expect($payment->paymentInstallments)->toHaveCount(1)
        ->and($payment->paymentInstallments->first()->installment->is($installment))->toBeTrue()
        ->and($installment->payments->first()->is($payment))->toBeTrue();
});

test('relaciones de la solicitud de demo y sus logs', function () {
    $demo = DemoRequest::factory()->create();
    $log = DemoRequestLog::create([
        'demo_request_id' => $demo->id,
        'old_status' => 'new',
        'new_status' => 'contacted',
        'comment' => 'Se contactó por teléfono',
    ]);

    expect($demo->system)->toBeInstanceOf(System::class)
        ->and($demo->logs->first()->is($log))->toBeTrue()
        ->and($log->demoRequest->is($demo))->toBeTrue()
        ->and($demo->logs()->count())->toBe(1);
});

test('el eager loading evita N+1 al cargar planes de sistemas', function () {
    System::factory()->count(5)->create()->each(
        fn (System $system) => SystemPlan::factory()->create(['system_id' => $system->id])
    );

    DB::flushQueryLog();
    DB::enableQueryLog();

    System::with('plans')->get();

    expect(count(DB::getQueryLog()))->toBe(2);
});
