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
        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $totalAmount = $product->amount * $quantity;

        $transaction = DB::transaction(function () use ($data, $product, $quantity, $totalAmount) {
            $transaction = Transaction::create([
                'client_id' => $data['client_id'],
                'gateway_id' => $data['gateway_id'],
                'amount' => $totalAmount,
                'status' => 'pending',
                'card_last_numbers' => substr($data['card_number'], -4),
            ]);

            $transaction->products()->attach($product->id, [
                'quantity' => $quantity,
                'price_at_purchase' => $product->amount
            ]);

            return $transaction;
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
                'transaction' => $transaction->load(['client', 'products', 'gateway']),
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
        // Carregamos 'products' com os dados da tabela pivô (quantity, price_at_purchase)
        $paginator = Transaction::with(['client', 'gateway', 'products'])
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
            'data' => $paginator->getCollection()->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'external_id' => $transaction->external_id,
                    'status' => $transaction->status,
                    'amount' => (float) $transaction->amount,
                    'card_last_numbers' => $transaction->card_last_numbers,
                    'date' => $transaction->created_at->format('d/m/Y H:i'),
                    'client' => [
                        'name' => $transaction->client->name,
                        'email' => $transaction->client->email,
                    ],
                    'gateway' => $transaction->gateway->name,
                    'items' => $transaction->products->map(function ($product) {
                        return [
                            'name' => $product->name,
                            'qty' => $product->pivot->quantity,
                            'price_unit' => (float) $product->pivot->price_at_purchase,
                            'subtotal' => (float) ($product->pivot->quantity * $product->pivot->price_at_purchase)
                        ];
                    }),
                ];
            }),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

}