<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentResponse;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayTwoDriver implements PaymentGatewayInterface
{

    public function payCreditCard(array $data): PaymentResponse
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
            'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f',
            'Content-Type' => 'application/json'
        ])->post('http://localhost:3002/transacoes', [
                    'valor' => $data['amount'],
                    'nome' => $data['card_holder'],
                    'email' => $data['email'],
                    'numeroCartao' => $data['card_number'],
                    'cvv' => $data['cvv'],
                ]);

        $statusDescription = 'paid';
        $isSuccess = true;

        if ($response->json('statusCode') === 400) {
            $statusDescription = $response->json('erros.0.message') ?? 'failed';
            $isSuccess = false;
        }

        return new PaymentResponse(
            success: $isSuccess,
            external_id: $response->json('id'),
            status: $statusDescription
        );
    }

    public function boleto(array $data): PaymentResponse
    {
        throw new Exception("O Gateway 2 não suporta pagamentos via Boleto no momento.");
    }

    public function pix(array $data): PaymentResponse
    {
        throw new Exception("O Gateway 2 não suporta pagamentos via Pix no momento.");
    }
}