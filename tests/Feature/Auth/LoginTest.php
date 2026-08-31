<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\LoginLog;
use App\Models\User;

it('muestra el formulario de login', function () {
    $this->get(route('login'))->assertOk()->assertSee('Iniciar sesión');
});

it('inicia sesión con credenciales válidas y redirige al admin', function () {
    $user = adminUser(['email' => 'admin@test.com', 'password' => 'Passw0rd!']);

    $this->post(route('login.attempt'), [
        'email' => 'admin@test.com',
        'password' => 'Passw0rd!',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
    expect(LoginLog::query()->where('user_id', $user->id)->where('success', true)->count())->toBe(1);
});

it('inicia sesión y redirige al cliente', function () {
    customerUser(['email' => 'cli@test.com', 'password' => 'Passw0rd!']);

    $this->post(route('login.attempt'), [
        'email' => 'cli@test.com',
        'password' => 'Passw0rd!',
    ])->assertRedirect(route('client.dashboard'));
});

it('rechaza credenciales inválidas y registra el intento', function () {
    $this->post(route('login.attempt'), [
        'email' => 'nadie@test.com',
        'password' => 'incorrecta',
    ])->assertSessionHasErrors('email');

    expect(LoginLog::query()->where('success', false)->where('email', 'nadie@test.com')->count())->toBe(1);
});

it('bloquea el login tras varios intentos fallidos (rate limit)', function () {
    adminUser(['email' => 'brute@test.com']);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('login.attempt'), [
            'email' => 'brute@test.com',
            'password' => 'incorrecta',
        ]);
    }

    $this->post(route('login.attempt'), [
        'email' => 'brute@test.com',
        'password' => 'incorrecta',
    ])->assertTooManyRequests();
});

it('redirige a cambio de contraseña cuando must_change_password está activo', function () {
    adminUser([
        'email' => 'cambio@test.com',
        'password' => 'Passw0rd!',
        'must_change_password' => true,
    ]);

    $this->post(route('login.attempt'), [
        'email' => 'cambio@test.com',
        'password' => 'Passw0rd!',
    ])->assertRedirect(route('password.change'));
});

it('impide el acceso a usuarios inactivos', function () {
    adminUser([
        'email' => 'inactive@test.com',
        'password' => 'Passw0rd!',
        'is_active' => false,
    ]);

    $this->post(route('login.attempt'), [
        'email' => 'inactive@test.com',
        'password' => 'Passw0rd!',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('cierra sesión', function () {
    $user = adminUser();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('registra last_login_at al iniciar sesión', function () {
    adminUser(['email' => 'last@test.com', 'password' => 'Passw0rd!']);

    $this->post(route('login.attempt'), [
        'email' => 'last@test.com',
        'password' => 'Passw0rd!',
    ])->assertRedirect(route('admin.dashboard'));

    expect(User::query()->where('email', 'last@test.com')->first()->last_login_at)->not->toBeNull();
});

it('los roles se asignan correctamente', function () {
    $user = roleUser([], UserRole::Operator);

    expect($user->hasRole(UserRole::Operator))->toBeTrue()
        ->and($user->hasAnyRole([UserRole::SuperAdmin, UserRole::Operator]))->toBeTrue()
        ->and($user->isCustomer())->toBeFalse();
});
