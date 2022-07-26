<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\ExecutePaymentOrder;

class ExecutePaymentOrderRequest
{
    public readonly string $transactionId;

    public readonly ?string $authCode;

    public function __construct(string $transactionId, ?string $authCode)
    {
        $this->transactionId = $transactionId;
        $this->authCode = $authCode;
    }
}
