<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:Male,Female'],
            'dob' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'member_count' => ['nullable', 'integer', 'min:1'],
            'address1' => ['nullable', 'string'],
            'address2' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'phones' => ['array'],
            'phones.*' => ['nullable', 'string', 'max:50'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'national_valid_until' => ['nullable', 'date'],
            'passport_id' => ['nullable', 'string', 'max:255'],
            'passport_valid_until' => ['nullable', 'date'],
            'visa_id' => ['nullable', 'string', 'max:255'],
            'visa_valid_until' => ['nullable', 'date'],
            'attachments' => ['array'],
            'attachments.*' => ['file', 'max:4096'],
        ];
    }
}
