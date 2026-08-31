<?php

declare(strict_types=1);

use App\Enums\LicenseStatus;
use App\Models\CustomerSystem;
use App\Models\License;
use Illuminate\Database\QueryException;

test('un contrato tiene una sola licencia', function () {
    $contract = CustomerSystem::factory()->create();

    License::factory()->create(['customer_system_id' => $contract->id]);
    License::factory()->create(['customer_system_id' => $contract->id]);
})->throws(QueryException::class);

test('la clave de licencia es única', function () {
    $key = 'AAAA-BBBB-CCCC-DDDD';

    License::factory()->create(['license_key' => $key]);
    License::factory()->create(['license_key' => $key]);
})->throws(QueryException::class);

test('la licencia hereda el estado del contrato por relación', function () {
    $contract = CustomerSystem::factory()->create();
    $license = License::factory()->suspended()->create(['customer_system_id' => $contract->id]);

    expect($license->status)->toBe(LicenseStatus::Suspended)
        ->and($license->customerSystem->is($contract))->toBeTrue();
});
