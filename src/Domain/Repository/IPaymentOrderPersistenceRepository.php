<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\PaymentOrder;

interface IPaymentOrderPersistenceRepository
{
    public function persist(PaymentOrder $order): void;
}