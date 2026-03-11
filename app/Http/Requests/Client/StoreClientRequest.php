<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Client;


class StoreClientRequest extends FormRequest
{

    public function authorize(): bool
    {
        $response = Gate::inspect('create', Client::class);

        if ($response->allowed()) {
            return true;
        }

        throw new AuthorizationException($response->message());
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:clients,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já está sendo utilizado por outro usuário.',
        ];
    }
}
