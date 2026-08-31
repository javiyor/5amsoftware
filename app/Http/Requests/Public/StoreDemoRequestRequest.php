<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemoRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:180'],
            'email' => ['required', 'string', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'city' => ['nullable', 'string', 'max:120'],
            'system_id' => ['nullable', 'integer', 'exists:systems,id'],
            'number_of_users' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'date_format:H:i'],
            'comments' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
