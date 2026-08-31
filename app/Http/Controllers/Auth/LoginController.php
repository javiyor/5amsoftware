<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class LoginController
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $email = $request->string('email')->trim()->toString();
        $remember = $request->boolean('remember');

        $attempt = Auth::attempt(
            ['email' => $email, 'password' => $request->string('password')->toString()],
            $remember,
        );

        if (! $attempt) {
            $this->recordLog(null, $email, false);

            RateLimiter::hit('login', 5);

            return back()
                ->withErrors(['email' => 'Las credenciales no son correctas.'])
                ->onlyInput('email');
        }

        /** @var User $user */
        $user = Auth::user();

        $this->recordLog($user->id, $email, true);

        $user->forceFill(['last_login_at' => now()])->save();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Tu cuenta está desactivada. Contactate con el administrador.']);
        }

        if ($user->must_change_password) {
            return redirect()->route('password.change')->with('warning', 'Por seguridad, cambiá tu contraseña.');
        }

        return redirect()->intended($this->homeRoute($user));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function homeRoute(User $user): string
    {
        if ($user->hasAnyRole([UserRole::SuperAdmin, UserRole::Admin, UserRole::Operator])) {
            return route('admin.dashboard');
        }

        return route('client.dashboard');
    }

    private function recordLog(?int $userId, string $email, bool $success): void
    {
        try {
            LoginLog::query()->create([
                'user_id' => $userId,
                'email' => $email,
                'success' => $success,
                'ip' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
            ]);
        } catch (\Throwable) {
            // El log de acceso nunca debe romper el login.
        }
    }
}
