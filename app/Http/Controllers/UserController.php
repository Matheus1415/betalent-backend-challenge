<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\IndexUserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(IndexUserRequest $request)
    {
        try {
            $users = $this->userService->getAll($request->validated());
            return $this->success('Lista de usuários carregada com sucesso.', $users);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar lista de usuários', [], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->create($request->validated());

            return $this->success('Usuário criado com sucesso.', $user, 201);
        } catch (\Exception $e) {
            return $this->error('Erro ao criar usuário', [
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->success('Usuário encontrado com sucesso.', $user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Usuário não encontrado.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao buscar usuário.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $user = User::findOrFail($id);

            if (auth()->id() === $user->id) {
                return $this->error('Você não pode excluir sua própria conta.', [], 403);
            }

            $user->delete();

            return $this->success('Usuário excluído com sucesso.', [], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Usuário não encontrado para exclusão.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao excluir usuário.', ['error' => $e->getMessage()], 500);
        }
    }
}