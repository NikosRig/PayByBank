<?php

declare(strict_types=1);

namespace PayByBank\Domain;

use PayByBank\Domain\Entity\Transaction;

interface PaymentMethodResolver
{
    public function resolve(Transaction $transaction): PaymentMethod;
}
