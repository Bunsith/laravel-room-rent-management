<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $roles = Role::query()->pluck('name')->all();
        if (empty($roles)) {
            $roles = array_keys(config('permissions.roles', ['admin' => 'Admin', 'staff' => 'Staff']));
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role' => ['required', Rule::in($roles)],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}
