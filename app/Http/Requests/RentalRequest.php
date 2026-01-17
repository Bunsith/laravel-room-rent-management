<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'people' => ['required', 'integer', 'min:1'],
            'rent_date' => ['nullable', 'date'],
            'check_in' => ['nullable', 'date'],
            'expected_check_out' => ['nullable', 'date'],
            'room_fee' => ['required', 'numeric', 'min:0'],
            'deposit_fee' => ['nullable', 'numeric', 'min:0'],
            'partial_pay' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ];
    }
}
