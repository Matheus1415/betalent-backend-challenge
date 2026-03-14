<?php

namespace App\Services;

use App\DTOs\PaymentResponse;
use App\Services\Gateways\GatewayOneDriver;
use App\Services\Gateways\GatewayTwoDriver;
use Exception;

class PaymentGatewayService
{
    protected array $drivers = [
        'gateway-1' => GatewayOneDriver::class,
        'gateway-2' => GatewayTwoDriver::class,
    ];

    public function process(string $slug, array $data, string $method = 'credit_card'): PaymentResponse
    {
        if (!isset($this->drivers[$slug])) {
            throw new Exception("Gateway [{$slug}] não suportado.");
        }

        $driverClass = $this->drivers[$slug];
        $driver = new $driverClass();

        return match ($method) {
            'boleto'      => $driver->boleto($data),
            'pix'         => $driver->pix($data),
            'credit_card' => $driver->payCreditCard($data),
            default       => throw new Exception("Método de pagamento [{$method}] não suportado."),
        };
    }
}