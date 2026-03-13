<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gateway\StoreGatewayRequest;
use App\Http\Requests\Gateway\UpdateGatewayRequest;
use App\Http\Requests\Gateway\IndexGatewayRequest;
use App\Models\Gateway;
use App\Services\GatewayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GatewayController extends Controller
{
    public function __construct(protected GatewayService $gatewayService)
    {
    }

    public function index(IndexGatewayRequest $request)
    {
        try {
            $gateways = $this->gatewayService->getAll($request->validated(), $request->input('per_page', 10));
            return $this->success('Lista de gateways carregada.', $gateways);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar gateways.', [], 500);
        }
    }

    public function store(StoreGatewayRequest $request)
    {
        try {
            $gateway = $this->gatewayService->create($request->validated());
            return $this->success('Gateway configurado com sucesso.', $gateway, 201);
        } catch (\Exception $e) {
            return $this->error('Erro ao salvar gateway.', ['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $gateway = Gateway::findOrFail($id);
            return $this->success('Gateway encontrado.', $gateway);
        } catch (ModelNotFoundException $e) {
            return $this->error('Gateway não encontrado.', [], 404);
        }
    }

    public function update(UpdateGatewayRequest $request, int $id)
    {
        try {
            $gateway = Gateway::findOrFail($id);
            $updated = $this->gatewayService->update($gateway, $request->validated());
            return $this->success('Gateway atualizado com sucesso.', $updated);
        } catch (ModelNotFoundException $e) {
            return $this->error('Gateway não encontrado para atualização.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao atualizar.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $gateway = Gateway::findOrFail($id);
            $gateway->delete();
            return $this->success('Gateway removido com sucesso.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Gateway não encontrado para exclusão.', [], 404);
        }
    }
}