<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FloorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $floorId = $this->route('floor')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('floors', 'name')->ignore($floorId),
            ],
        ];
    }
}
