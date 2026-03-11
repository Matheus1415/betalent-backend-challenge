<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\IndexClientRequest;
use App\Models\Client;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(protected ClientService $userService)
    {
    }

    public function index(IndexClientRequest $request)
    {
        try {
            $client = $this->userService->getAll($request->validated());
            return $this->success('Lista de clientes carregada com sucesso.', $client);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar lista de clientes', [], 500);
        }
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $client = $this->userService->create($request->validated());

            return $this->success('Cliente criado com sucesso.', $client, 201);
        } catch (\Exception $e) {
            return $this->error('Erro ao criar cliente', [
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        $this->authorize('viewAny', Client::class);
        
        try {
            $client = Client::findOrFail($id);
            return $this->success('Cliente encontrado com sucesso.', $client);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Cliente não encontrado.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao buscar cliente.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateClientRequest $request, int $id)
    {
        try {
            $client = Client::findOrFail($id);

            $updatedUser = $this->userService->update($client, $request->validated());

            return $this->success('Cliente atualizado com sucesso.', $updatedUser);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Cliente não encontrado para atualização.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao atualizar cliente.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $client = Client::findOrFail($id);

            $this->authorize('delete', $client);

            $client->delete();

            return $this->success('Cliente excluído com sucesso.', [], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Cliente não encontrado para exclusão.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao excluir cliente.', ['error' => $e->getMessage()], 500);
        }
    }
}