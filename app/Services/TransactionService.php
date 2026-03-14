<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Gateway;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionService
{
    public function __construct(
        protected PaymentGatewayService $paymentGatewayService
    ) {
    }

    public function processPayment(array $data)
    {
        $transaction = DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $gateway = Gateway::findOrFail($data['gateway_id']);

            return Transaction::create([
                'client_id' => $data['client_id'],
                'product_id' => $product->id,
                'gateway_id' => $gateway->id,
                'amount' => $product->amount,
                'status' => 'pending',
                'card_last_numbers' => substr($data['card_number'], -4),
            ]);
        });

        try {
            $paymentPayload = array_merge($data, [
                'amount' => $transaction->amount,
                'card_holder' => $transaction->client->name,
                'email' => $transaction->client->email,
            ]);

            $response = $this->paymentGatewayService->process($transaction->gateway->slug, $paymentPayload);

            $transaction->update([
                'external_id' => $response->external_id,
                'status' => $response->success ? 'paid' : 'failed',
            ]);

            return [
                'success' => $response->success,
                'transaction' => $transaction->load(['client', 'product', 'gateway']),
                'message' => $response->success ? 'Pagamento processado com sucesso.' : $response->status
            ];

        } catch (Exception $e) {
            $transaction->update(['status' => 'failed']);
            return [
                'success' => false,
                'transaction' => $transaction,
                'message' => 'Erro inesperado'
            ];
        }
    }

    public function getAll(array $filters = [], int $perPage = 10)
    {
        $paginator = Transaction::with(['client', 'product', 'gateway'])
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['client_id'] ?? null, function ($query, $clientId) {
                $query->where('client_id', $clientId);
            })
            ->when($filters['date_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ];
    }

}