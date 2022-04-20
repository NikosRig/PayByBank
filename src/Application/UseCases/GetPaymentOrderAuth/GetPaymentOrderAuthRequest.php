<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderAuth;

class GetPaymentOrderAuthRequest
{
    public readonly string $paymentOrderToken;

    public function __construct(string $paymentOrderToken)
    {
        $this->paymentOrderToken = $paymentOrderToken;
    }
}
