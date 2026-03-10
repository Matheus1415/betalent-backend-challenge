<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->authenticate($request->validated());

            return $this->success('Autenticação realizada com sucesso.', $result);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return $this->success('Logout realizado com sucesso.');

        } catch (\Exception $e) {
            return $this->error('Erro ao realizar logout', [], 500);
        }
    }
}