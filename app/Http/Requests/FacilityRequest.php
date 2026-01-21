<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $facilityId = $this->route('facility')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'name')->ignore($facilityId),
            ],
        ];
    }
}
