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

    public readonly string $bank;

    public readonly CreditorAccount $creditorAccount;
    
    public function __construct(
        DateTime $dateCreated,
        PaymentOrderStatus $status,
        string $token,
        int $amount,
        string $bank,
        CreditorAccount $creditorAccount
    ) {
        $this->dateCreated = $dateCreated;
        $this->status = $status;
        $this->token = $token;
        $this->amount = $amount;
        $this->bank = $bank;
        $this->creditorAccount = $creditorAccount;
    }
}
