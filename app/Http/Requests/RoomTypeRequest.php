<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomTypeId = $this->route('room_type')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('room_types', 'name')->ignore($roomTypeId),
            ],
        ];
    }
}
