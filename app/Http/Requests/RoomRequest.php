<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'floor_id' => ['required', 'exists:floors,id'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'stay_type' => ['required', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'facilities' => ['array'],
            'facilities.*' => ['exists:facilities,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
