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
}