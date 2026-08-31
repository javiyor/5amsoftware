<?php

declare(strict_types=1);

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

it('muestra el formulario de olvido de contraseña', function () {
    $this->get(route('password.request'))->assertOk()->assertSee('Restablecer contraseña');
});

it('envía enlace de restablecimiento a un correo existente', function () {
    Notification::fake();

    $user = roleUser(['email' => 'reset@test.com']);

    $this->post(route('password.email'), ['email' => 'reset@test.com'])
        ->assertRedirect()
        ->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('no revela si el correo no existe (respuesta genérica)', function () {
    $this->post(route('password.email'), ['email' => 'no-existe@test.com'])
        ->assertSessionHas('status');
});

it('permite restablecer la contraseña con un token válido', function () {
    $user = roleUser(['email' => 'final@test.com']);

    $token = app('auth.password.broker')->createToken($user);

    $this->post(route('password.store'), [
        'token' => $token,
        'email' => 'final@test.com',
        'password' => 'NuevaPass123!',
        'password_confirmation' => 'NuevaPass123!',
    ])->assertRedirect(route('login'));

    $this->assertTrue(Hash::check(
        'NuevaPass123!',
        $user->fresh()->password,
    ));
});

it('rechaza un token inválido', function () {
    $user = roleUser(['email' => 'token@test.com']);

    $this->post(route('password.store'), [
        'token' => 'token-invalido',
        'email' => 'token@test.com',
        'password' => 'NuevaPass123!',
        'password_confirmation' => 'NuevaPass123!',
    ])->assertSessionHasErrors();
});
