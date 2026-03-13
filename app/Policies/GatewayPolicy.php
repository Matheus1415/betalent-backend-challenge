<?php

namespace App\Policies;

use App\Models\Gateway;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GatewayPolicy
{

    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    public function view(User $user, Gateway $gateway): Response
    {
        return Response::allow();
    }

    public function create(User $user): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Apenas administradores podem configurar novos gateways de pagamento.');
    }

    public function update(User $user, Gateway $gateway): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Você não tem permissão para alterar as configurações deste gateway.');
    }

    public function delete(User $user, Gateway $gateway): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Apenas administradores podem remover gateways do sistema.');
    }
}