<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http;

use Exception;
use PayByBank\Domain\Entity\Transaction;

interface PaymentMethod
{
    /**
     * @throws Exception
     */
    public function createScaRedirectUrl(Transaction $transaction): void;
}
