<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Ingresá tu correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
            'password.required' => 'Ingresá tu contraseña.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }

    public static function passwordRules(): array
    {
        return ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->symbols()];
    }
}
