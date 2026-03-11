<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Client;


class IndexClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $response = Gate::inspect('viewAny', Client::class);

        if ($response->allowed()) {
            return true;
        }

        throw new AuthorizationException($response->message());
    }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|nullable|string|max:100',
            'email' => 'sometimes|nullable|string',
            'sort'  => 'sometimes|nullable|in:name,email,created_at',
            'order' => 'sometimes|nullable|in:asc,desc',
        ];
    }
}