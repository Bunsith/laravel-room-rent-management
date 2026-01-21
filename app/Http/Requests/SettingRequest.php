<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'default_currency' => ['required', 'string', 'max:3'],
            'deposit_default' => ['nullable', 'numeric', 'min:0'],
            'water_rate' => ['nullable', 'numeric', 'min:0'],
            'electric_rate' => ['nullable', 'numeric', 'min:0'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
