<?php

declare(strict_types=1);

use App\Enums\UserRole;

it('permite el acceso a /admin para super_admin', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('permite el acceso a /admin para admin y operator', function () {
    $this->actingAs(roleUser([], UserRole::Admin))
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs(roleUser([], UserRole::Operator))
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('deniega el acceso a /admin para un cliente (403)', function () {
    $this->actingAs(customerUser())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('deniega el acceso a /admin para usuarios sin rol (403)', function () {
    $this->actingAs(roleUser())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('redirige al login a usuarios no autenticados en /admin', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('permite el acceso a /cliente solo para clientes', function () {
    $customer = customerUser(['email_verified_at' => now()]);

    $this->actingAs($customer)
        ->get(route('client.dashboard'))
        ->assertOk();
});

it('deniega el acceso a /cliente para un admin (403)', function () {
    $this->actingAs(adminUser())
        ->get(route('client.dashboard'))
        ->assertForbidden();
});

it('exige verificación de email en el portal del cliente', function () {
    $customer = customerUser(['email_verified_at' => null]);

    $this->actingAs($customer)
        ->get(route('client.dashboard'))
        ->assertRedirect(route('verification.notice'));
});
