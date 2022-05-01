<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\Banks;

use PayByBank\Domain\Entity\Transaction;

interface Bank
{
    public function getAuthorizationUrl(Transaction $transaction): string;
}