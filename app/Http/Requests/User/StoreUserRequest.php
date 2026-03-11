<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,MANAGER,FINANCE,USER',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já está sendo utilizado por outro usuário.',
            'role.in' => 'O nível de acesso selecionado é inválido.',
        ];
    }
}
