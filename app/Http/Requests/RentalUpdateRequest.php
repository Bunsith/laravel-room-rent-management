<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'people' => ['required', 'integer', 'min:1'],
            'room_fee' => ['required', 'numeric', 'min:0'],
            'check_in' => ['nullable', 'date'],
            'expected_check_out' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}
