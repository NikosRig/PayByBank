<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderAuth;

class GetPaymentOrderAuthResponse
{
    public readonly string $bankName;

    public function __construct(string $bankName)
    {
        $this->bankName = $bankName;
    }
}
