<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class IndexTransactionRequest extends FormRequest
{

    public function authorize(): bool
    {
        $response = Gate::inspect('viewAny', Transaction::class);

        if ($response->allowed()) {
            return true;
        }

        throw new AuthorizationException($response->message());
    }

    public function rules(): array
    {
        return [
            'client_id'  => 'sometimes|exists:clients,id',
            'status'     => 'sometimes|in:pending,paid,failed,refunded',
            'date_from'  => 'sometimes|date',
            'date_to'    => 'sometimes|date|after_or_equal:date_from',
            'per_page'   => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.exists'      => 'O cliente selecionado para o filtro não existe.',
            'status.in'             => 'O status selecionado é inválido.',
            'date_from.date'        => 'A data inicial deve ser uma data válida.',
            'date_to.date'          => 'A data final deve ser uma data válida.',
            'date_to.after_or_equal' => 'A data final não pode ser anterior à data inicial.',
            'per_page.integer'      => 'A quantidade por página deve ser um número.',
            'per_page.max'          => 'Você só pode visualizar até 100 transações por vez.',
        ];
    }
}