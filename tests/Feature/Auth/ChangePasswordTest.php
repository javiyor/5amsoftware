<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Hash;

it('requiere autenticación para cambiar la contraseña', function () {
    $this->get(route('password.change'))->assertRedirect(route('login'));
});

it('cambia la contraseña con la actual correcta', function () {
    $user = adminUser([
        'email' => 'cambio2@test.com',
        'password' => 'Actual123!',
        'must_change_password' => true,
    ]);

    $this->actingAs($user)
        ->post(route('password.change.update'), [
            'current_password' => 'Actual123!',
            'password' => 'NuevaPass123!',
            'password_confirmation' => 'NuevaPass123!',
        ])->assertRedirect(route('home'));

    $user->refresh();

    expect(Hash::check('NuevaPass123!', $user->password))->toBeTrue()
        ->and($user->must_change_password)->toBeFalse();
});

it('rechaza el cambio con contraseña actual incorrecta', function () {
    $user = adminUser(['password' => 'Actual123!']);

    $this->actingAs($user)
        ->post(route('password.change.update'), [
            'current_password' => 'MalActual!',
            'password' => 'NuevaPass123!',
            'password_confirmation' => 'NuevaPass123!',
        ])->assertSessionHasErrors('current_password');
});
