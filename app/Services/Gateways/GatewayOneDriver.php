<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentResponse;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayOneDriver implements PaymentGatewayInterface
{

    public function payCreditCard(array $data): PaymentResponse
    {
        $bearerToken = $this->authenticate();

        $response = Http::withToken($bearerToken)
            ->post('http://localhost:3001/transactions', [
                'amount' => $data['amount'],
                'name' => $data['card_holder'],
                'email' => $data['email'],
                'cardNumber' => $data['card_number'],
                'cvv' => $data['cvv'],
            ]);

        $statusDescription = $response->successful() 
        ? 'paid' 
        : ($response->json('error') ?? 'failed');

        return new PaymentResponse(
            success:     $response->successful(),
            external_id: $response->json('id'),
            status:      $statusDescription
        );
    }

    public function boleto(array $data): PaymentResponse
    {
        throw new Exception("O Gateway 1 não suporta pagamentos via Boleto no momento.");
    }

    public function pix(array $data): PaymentResponse
    {
        throw new Exception("O Gateway 1 não suporta pagamentos via Pix no momento.");
    }

    private function authenticate(): string
    {
        $response = Http::post('http://localhost:3001/login', [
            'email' => 'dev@betalent.tech',
            'token' => 'FEC9BB078BF338F464F96B48089EB498',
        ]);

        if ($response->failed()) {
            throw new Exception("Falha na autenticação com o Gateway 1.");
        }

        return $response->json('token') ?? 'FEC9BB078BF338F464F96B48089EB498';
    }
}