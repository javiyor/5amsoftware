<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController
{
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        // Respuesta genérica en ambos casos para no revelar si el correo existe.
        return back()->with('status', __(
            $status === Password::RESET_LINK_SENT
                ? 'Se envió el enlace de restablecimiento a tu correo.'
                : 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.',
        ));
    }
}
