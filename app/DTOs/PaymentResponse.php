<?php

namespace App\DTOs;

class PaymentResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $external_id,
        public readonly string $status,
    ) {}
}