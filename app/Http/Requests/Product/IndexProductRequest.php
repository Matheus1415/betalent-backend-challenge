<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'sort' => 'sometimes|nullable|in:name,email,created_at',
            'order' => 'sometimes|nullable|in:asc,desc',
            'amount' => 'sometimes|nullable|numeric'
        ];
    }

}