<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{

    public function viewAny(User $user): Response
    {
        return $user->role === 'ADMIN' || $user->role === 'MANAGER'
            ? Response::allow()
            : Response::deny('Você não tem permissão para acessar o relatório de transações.');
    }

    public function view(User $user, Transaction $transaction): Response
    {
        return $user->role === 'ADMIN' || $user->id === $transaction->user_id
            ? Response::allow()
            : Response::deny('Esta transação não pertence à sua conta.');
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }

    public function refund(User $user, Transaction $transaction): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Apenas administradores podem autorizar o estorno de pagamentos.');
    }
}