<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http;

use Exception;

interface PaymentMethodResolver
{
    /**
     * @throws Exception
     */
    public function resolveWithBankCode(string $bankCode): PaymentMethod;
}
