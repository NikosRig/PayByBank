<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

enum PaymentOrderStatus: int
{
    case CREATED = 1;
    case AUTHORIZED = 2;
    case PAID = 3;
    case CANCELED = 4;

    public function isStatusCreated(): bool
    {
        return $this->value === self::CREATED->value;
    }

    public function isStatusAuthorized(): bool
    {
        return $this->value === self::AUTHORIZED->value;
    }
}
