<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{

    public function viewAny(User $user): Response
    {
        return in_array($user->role, ['ADMIN', 'MANAGER', 'FINANCE'])
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar a lista de clientes.');
    }


    public function view(User $user, Client $client): Response
    {
        return in_array($user->role, ['ADMIN', 'MANAGER', 'FINANCE'])
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar os detalhes deste cliente.');
    }

    public function create(User $user): Response
    {
        return in_array($user->role, ['ADMIN', 'MANAGER'])
            ? Response::allow()
            : Response::deny('Apenas administradores ou gerentes podem cadastrar novos clientes.');
    }

    public function update(User $user, Client $client): Response
    {
        return in_array($user->role, ['ADMIN', 'MANAGER'])
            ? Response::allow()
            : Response::deny('Você não tem permissão para editar informações de clientes.');
    }

    public function delete(User $user, Client $client): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Apenas administradores podem excluir clientes do sistema.');
    }
}