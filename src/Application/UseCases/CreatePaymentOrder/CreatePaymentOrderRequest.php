<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderRequest
{
    public readonly int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
