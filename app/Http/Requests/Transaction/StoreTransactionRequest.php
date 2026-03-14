<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class StoreTransactionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'   => 'required|exists:clients,id',
            'product_id'  => 'required|exists:products,id',
            'gateway_id'  => 'sometimes|exists:gateways,id',
            
            'card_number' => 'required|string|size:16',
            'cvv'         => 'required|string|size:3',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required'   => 'Identificar o cliente é obrigatório.',
            'client_id.exists'     => 'O cliente selecionado não existe em nossa base.',
            'product_id.required'  => 'Um produto deve ser selecionado para a venda.',
            'product_id.exists'    => 'O produto selecionado é inválido.',
            'gateway_id.exists'    => 'O gateway selecionado não está disponível.',
            
            'card_number.required' => 'O número do cartão é obrigatório.',
            'card_number.size'     => 'O número do cartão deve conter exatamente 16 dígitos.',
            'cvv.required'         => 'O código de segurança (CVV) é obrigatório.',
            'cvv.size'             => 'O CVV deve ter 3 dígitos.',
        ];
    }
}