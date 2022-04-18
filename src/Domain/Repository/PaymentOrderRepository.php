<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\PaymentOrder;

interface PaymentOrderRepository
{
    public function findByToken(string $paymentOrderToken): ?PaymentOrder;

    public function save(PaymentOrder $paymentOrder): void;
}
