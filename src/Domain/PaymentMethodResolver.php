<?php

declare(strict_types=1);

namespace PayByBank\Domain;

use PayByBank\Domain\Entity\BankAccount;

interface PaymentMethodResolver
{
    public function resolve(BankAccount $bankAccount): PaymentMethod;
}
