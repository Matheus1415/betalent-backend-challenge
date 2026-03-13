<?php

namespace App\Http\Requests\Gateway;

use App\Models\Gateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class StoreGatewayRequest extends FormRequest
{

    public function authorize(): bool
    {
        $response = Gate::inspect('create', Gateway::class);

        if ($response->allowed()) {
            return true;
        }

        throw new AuthorizationException($response->message());
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'priority'  => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'O nome do gateway é obrigatório.',
            'name.string'        => 'O nome deve ser um texto válido.',
            'slug.required'      => 'O identificador (slug) é obrigatório.',
            'slug.unique'        => 'Este slug já está em uso por outro gateway.',
            'is_active.required' => 'Você deve informar se o gateway está ativo ou não.',
            'is_active.boolean'  => 'O campo ativo deve ser verdadeiro ou falso.',
            'priority.required'  => 'A prioridade é obrigatória.',
            'priority.integer'   => 'A prioridade deve ser um número inteiro.',
            'priority.min'       => 'A prioridade mínima permitida é 1.',
        ];
    }
}