<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'sometimes|string|min:3|max:255',
            'amount' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.min'     => 'O nome atualizado deve ter pelo menos 3 caracteres.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min'     => 'O valor do produto não pode ser negativo.',
        ];
    }
}