<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'email' => 'sometimes|email|unique:clients,email,' . $userId,
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já pertence a outro usuário.',
        ];
    }
}
