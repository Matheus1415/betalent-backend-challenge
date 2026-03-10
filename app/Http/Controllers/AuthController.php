<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

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
}