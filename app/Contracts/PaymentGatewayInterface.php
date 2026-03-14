<?php

namespace App\Contracts;

use App\DTOs\PaymentResponse;

interface PaymentGatewayInterface
{
    public function payCreditCard(array $data): PaymentResponse;
    public function boleto(array $data): PaymentResponse;
    public function pix(array $data): PaymentResponse;
}   