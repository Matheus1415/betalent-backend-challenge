<?php

namespace App\Http\Requests\Gateway;

use App\Models\Gateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class IndexGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        $response = Gate::inspect('viewAny', Gateway::class);

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
            'per_page'  => 'sometimes|integer|min:1|max:50',
        ];
    }
}