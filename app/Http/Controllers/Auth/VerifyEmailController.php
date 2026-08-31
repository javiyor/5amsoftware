<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyEmailController
{
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('home')->with('status', __('Tu correo fue verificado.'));
    }

    public function send(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', __('Enviamos un nuevo enlace de verificación a tu correo.'));
    }
}
