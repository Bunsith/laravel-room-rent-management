<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'account_type_id' => ['nullable', 'exists:account_types,id'],
            'resource_budget_id' => ['nullable', 'exists:resource_budgets,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'method' => ['required', 'string', 'max:50'],
            'floor_id' => ['nullable', 'exists:floors,id'],
            'attachment' => ['nullable', 'file', 'max:4096'],
        ];
    }
}
