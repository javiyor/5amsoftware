<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Ingresá tu correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
        ];
    }

    public function sendResetLink(): array
    {
        $status = Password::sendResetLink($this->only('email'));

        return [
            'status' => $status,
            'ok' => in_array($status, [
                Password::RESET_LINK_SENT,
                Password::INVALID_USER,
            ], true),
        ];
    }
}
