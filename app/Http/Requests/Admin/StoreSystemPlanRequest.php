<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSystemPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('slug') || blank($this->input('slug'))) {
            $this->merge(['slug' => Str::slug((string) $this->input('name'))]);
        }
    }

    public function rules(): array
    {
        $system = $this->route('system');

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:190', 'unique:system_plans,slug,NULL,id,system_id,'.$system->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'setup_price' => ['nullable', 'numeric', 'min:0'],
            'billing_interval_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'grace_days' => ['required', 'integer', 'min:0', 'max:365'],
            'max_users' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
