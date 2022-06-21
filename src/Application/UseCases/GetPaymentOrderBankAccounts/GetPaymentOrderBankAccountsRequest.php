<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderBankAccounts;

class GetPaymentOrderBankAccountsRequest
{
    public readonly string $paymentOrderToken;

    public function __construct(string $paymentOrderToken)
    {
        $this->paymentOrderToken = $paymentOrderToken;
    }
}
