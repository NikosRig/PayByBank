<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\PaymentOrder;

interface PaymentOrderStoreRepository
{
    public function store(PaymentOrder $paymentOrder): void;
}