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
            $transaction = Transaction::with(['client', 'gateway', 'products'])
                ->findOrFail($id);

            $formattedTransaction = [
                'id' => $transaction->id,
                'external_id' => $transaction->external_id,
                'status' => $transaction->status,
                'amount' => (float) $transaction->amount,
                'card_last_numbers' => $transaction->card_last_numbers,
                'created_at' => $transaction->created_at->format('d/m/Y H:i:s'),
                'updated_at' => $transaction->updated_at->format('d/m/Y H:i:s'),

                'client' => [
                    'id' => $transaction->client->id,
                    'name' => $transaction->client->name,
                    'email' => $transaction->client->email,
                ],

                'gateway' => [
                    'name' => $transaction->gateway->name,
                    'slug' => $transaction->gateway->slug,
                ],

                'items' => $transaction->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'qty' => $product->pivot->quantity,
                        'price_unit' => (float) $product->pivot->price_at_purchase,
                        'subtotal' => (float) ($product->pivot->quantity * $product->pivot->price_at_purchase)
                    ];
                }),
            ];

            return $this->success('Detalhes da transação carregados.', $formattedTransaction);

        } catch (ModelNotFoundException $e) {
            return $this->error('Transação não encontrada.', 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar detalhes da transação: ' . $e->getMessage(), 500);
        }
    }
}