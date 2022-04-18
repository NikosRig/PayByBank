<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

enum PaymentOrderStatus: int
{
    case PENDING = 1;

    public function isPending(): bool
    {
        return $this->value === self::PENDING->value;
    }
}
