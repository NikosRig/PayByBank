<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use PayByBank\Domain\ValueObjects\PaymentOrderStatus;

class PaymentOrder
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly int $amount;

    private int $status;

    public readonly string $token;

    public function __construct(string $creditorIban, string $creditorName, int $amount)
    {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
        $this->status = PaymentOrderStatus::PENDING_CONSENT->value;
        $this->token = md5(microtime(true).mt_Rand());
    }
}
