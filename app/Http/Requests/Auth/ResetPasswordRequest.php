<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Ingresá una nueva contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
