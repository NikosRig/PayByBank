<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderRequest
{
    public readonly int $amount;

    public readonly string $accessToken;

    public function __construct(int $amount, string $accessToken)
    {
        $this->amount = $amount;
        $this->accessToken = $accessToken;
    }
}
