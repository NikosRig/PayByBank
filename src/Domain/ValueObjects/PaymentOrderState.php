<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class PaymentOrderState
{
    public function __construct(
        public readonly DateTime $dateCreated,
        public readonly PaymentOrderStatus $status,
        public readonly string $token,
        public readonly int $amount,
        public readonly string $merchantId,
        public readonly string $description,
        public readonly string $id
    ) {
    }
}
