<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetMerchantBankAccounts;

class GetMerchantBankAccountsRequest
{
    public readonly string $merchantId;

    public function __construct(string $merchantId)
    {
        $this->merchantId = $merchantId;
    }
}