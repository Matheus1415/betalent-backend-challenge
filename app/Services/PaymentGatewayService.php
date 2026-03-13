<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{

    public function createTransactionOnGateway1(array $data)
    {
        $response = Http::withToken('FEC9BB078BF338F464F96B48089EB498')
            ->post(config('services.gateway1.url') . '/transactions', [
                'amount'     => $data['amount'] * 100,
                'name'       => $data['name'],
                'email'      => $data['email'],
                'cardNumber' => $data['card_number'],
                'cvv'        => $data['cvv'],
            ]);

        return $response->json();
    }

    public function createTransactionOnGateway2(array $data)
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
            'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f'
        ])->post(config('services.gateway2.url') . '/transacoes', [
            'valor'        => $data['amount'],
            'nome'         => $data['name'],
            'email'        => $data['email'],
            'numeroCartao' => $data['card_number'],
            'cvv'          => $data['cvv'],
        ]);

        return $response->json();
    }
}