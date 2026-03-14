<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\IndexTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {
    }

    public function index(IndexTransactionRequest $request)
    {
        try {
            $transactions = $this->transactionService->getAll($request->all());
            return $this->success('Relatório de transações carregado.', $transactions);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar lista de transações', [], 500);
        }
    }

    /** 
     * Cria uma nova transação e processa o pagamento no Gateway.
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            $serviceResponse = $this->transactionService->processPayment($request->validated());

            if (!$serviceResponse['success']) {
                return $this->error(
                    $serviceResponse['message'],
                    ['transaction' => $serviceResponse['transaction']],
                    402,
                );
            }

            return $this->success(
                $serviceResponse['message'],
                $serviceResponse['transaction']
            );

        } catch (\Exception $e) {
            return $this->error('Falha ao processar transação.', [
                'details' => $e->getMessage()
            ], 422);
        }
    }

    public function show(int $id)
    {

        try {
            $transaction = Transaction::findOrFail($id);
            return $this->success('Detalhes da transação carregados.', $transaction);
        } catch (ModelNotFoundException $e) {
            return $this->error('Transação não encontrada.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar detalhes da transação', [], 500);
        }
    }
}