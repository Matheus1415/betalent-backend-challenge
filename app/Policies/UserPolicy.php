<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function viewAny(User $authenticated): Response
    {
        return in_array($authenticated->role, ['ADMIN', 'MANAGER'])
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar a lista de usuários.');
    }

    public function create(User $user): Response
    {
        return $user->role === 'ADMIN'
            ? Response::allow()
            : Response::deny('Apenas administradores podem cadastrar novos usuários.');
    }

    public function delete(User $authenticated, User $targetUser): Response
    {
        if ($authenticated->role !== 'ADMIN') {
            return Response::deny('Você não tem permissão de Administrador para excluir usuários.');
        }

        if ($authenticated->id === $targetUser->id) {
            return Response::deny('Você não pode excluir sua própria conta.');
        }

        return Response::allow();
    }
}