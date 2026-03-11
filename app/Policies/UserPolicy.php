<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function viewAny(User $authenticated): bool
    {
        return in_array($authenticated->role, ['ADMIN', 'MANAGER']);
    }

    public function create(User $authenticated): bool
    {
        return $authenticated->role === 'ADMIN';
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