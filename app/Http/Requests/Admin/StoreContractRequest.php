<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\CustomerSystemStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'system_id' => ['required', 'integer', 'exists:systems,id'],
            'system_plan_id' => ['nullable', 'integer', 'exists:system_plans,id'],
            'custom_name' => ['nullable', 'string', 'max:180'],
            'started_at' => ['nullable', 'date'],
            'billing_started_at' => ['nullable', 'date'],
            'billing_interval_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'grace_days' => ['required', 'integer', 'min:0', 'max:365'],
            'monthly_amount' => ['required', 'numeric', 'min:0'],
            'setup_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'automatic_billing' => ['sometimes', 'boolean'],
            'automatic_suspension' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::enum(CustomerSystemStatus::class)],
            'manual_suspension' => ['sometimes', 'boolean'],
            'manual_suspension_reason' => ['nullable', 'string', 'max:1000'],
            'grace_override_until' => ['nullable', 'date', 'after_or_equal:today'],
            'domain' => ['nullable', 'string', 'max:255'],
            'installation_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
