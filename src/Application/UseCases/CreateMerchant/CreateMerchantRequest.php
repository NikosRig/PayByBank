<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

class CreateMerchantRequest
{
    public readonly string $merchantName;

    public function __construct(string $merchantName)
    {
        $this->merchantName = $merchantName;
    }
}
