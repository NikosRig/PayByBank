<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class PaymentOrderState
{
    public readonly DateTime $dateCreated;

    public readonly PaymentOrderStatus $status;

    public readonly string $token;

    public readonly int $amount;

    public readonly string $merchantId;

    public readonly string $description;

    public function __construct(
        DateTime $dateCreated,
        PaymentOrderStatus $status,
        string $token,
        int $amount,
        string $merchantId,
        string $description
    ) {
        $this->dateCreated = $dateCreated;
        $this->status = $status;
        $this->token = $token;
        $this->amount = $amount;
        $this->merchantId = $merchantId;
        $this->description = $description;
    }
}
