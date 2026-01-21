<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUtilitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'water_units' => ['nullable', 'numeric', 'min:0'],
            'electric_units' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
