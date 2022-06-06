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

    public function __construct(
        DateTime $dateCreated,
        PaymentOrderStatus $status,
        string $token,
        int $amount
    ) {
        $this->dateCreated = $dateCreated;
        $this->status = $status;
        $this->token = $token;
        $this->amount = $amount;
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'status' => $this->status->value,
            'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s'),
            'amount' => $this->amount
        ];
    }
}
