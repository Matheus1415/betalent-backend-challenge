<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|min:3|max:255',
            'amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'O nome do produto é obrigatório.',
            'name.min'        => 'O nome deve ter pelo menos 3 caracteres.',
            'amount.required' => 'O valor (amount) do produto é obrigatório.',
            'amount.numeric'  => 'O valor deve ser um número válido.',
            'amount.min'      => 'O valor do produto não pode ser negativo.',
        ];
    }
}