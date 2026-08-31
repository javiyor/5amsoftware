<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSystemRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:190', 'unique:systems,slug'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'main_image' => ['nullable', 'string', 'max:255'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'installation_price' => ['nullable', 'numeric', 'min:0'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
