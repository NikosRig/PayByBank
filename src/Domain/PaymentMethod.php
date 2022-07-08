<?php

declare(strict_types=1);

namespace PayByBank\Domain;

use Exception;
use PayByBank\Domain\Entity\Transaction;

interface PaymentMethod
{
    public function getBankCode(): string;

    /**
     * @throws Exception
     */
    public function createScaRedirectUrl(Transaction $transaction): void;
}
