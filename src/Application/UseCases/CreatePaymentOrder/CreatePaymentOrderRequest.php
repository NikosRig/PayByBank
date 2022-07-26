<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderRequest
{
    public readonly int $amount;

    public readonly string $accessToken;

    public readonly string $description;

    public function __construct(int $amount, string $accessToken, string $description)
    {
        $this->amount = $amount;
        $this->accessToken = $accessToken;
        $this->description = $description;
    }
}
