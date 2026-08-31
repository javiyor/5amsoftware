<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\CustomerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
            'business_name' => ['nullable', 'string', 'max:180'],
            'tax_id' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::enum(CustomerStatus::class)],
            'notes' => ['nullable', 'string', 'max:3000'],
            'registered_at' => ['nullable', 'date'],
        ];
    }
}
