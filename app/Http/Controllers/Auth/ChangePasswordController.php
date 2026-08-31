<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ChangePasswordController
{
    public function showChangePasswordForm(): View
    {
        return view('auth.change-password');
    }

    public function change(ChangePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->string('password')->toString()),
            'must_change_password' => false,
        ])->save();

        Auth::logoutOtherDevices($request->string('password')->toString());

        return redirect()->route('home')
            ->with('status', __('Tu contraseña fue actualizada.'));
    }
}
