<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use PayByBank\Domain\ValueObjects\PaymentOrderStatus;

class PaymentOrder
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly float $amount;

    private int $status;

    public function __construct(string $creditorIban, string $creditorName, float $amount)
    {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
        $this->status = PaymentOrderStatus::PENDING_CONSENT->value;
    }
}
