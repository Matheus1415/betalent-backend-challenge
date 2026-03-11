<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => 'sometimes|string|min:3|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:ADMIN,MANAGER,FINANCE,USER',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já pertence a outro usuário.',
            'role.in' => 'A role fornecida é inválida.',
        ];
    }
}
