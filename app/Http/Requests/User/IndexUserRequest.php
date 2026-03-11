<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;


class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $response = Gate::inspect('viewAny', User::class);

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
            'role'  => 'sometimes|nullable|in:ADMIN,MANAGER,FINANCE,USER',
            'sort'  => 'sometimes|nullable|in:name,email,created_at',
            'order' => 'sometimes|nullable|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'A role para filtro deve ser ADMIN, MANAGER, FINANCE ou USER.',
        ];
    }
}