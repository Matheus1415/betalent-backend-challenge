<?php

namespace App\Http\Requests\Gateway;

use App\Models\Gateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateGatewayRequest extends FormRequest
{

    public function authorize(): bool
    {
        $response = Gate::inspect('update', Gateway::class);

        if ($response->allowed()) {
            return true;
        }

        throw new AuthorizationException($response->message());
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
            'priority'  => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string'        => 'O nome deve ser um texto válido.',
            'slug.unique'        => 'Este identificador (slug) já está sendo usado por outro gateway.',
            'is_active.boolean'  => 'O campo ativo deve ser verdadeiro ou falso.',
            'priority.integer'   => 'A prioridade deve ser um número inteiro.',
            'priority.min'       => 'A prioridade mínima permitida é 1.',
        ];
    }
}