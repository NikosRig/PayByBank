<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\PaymentOrderStatus;

class PaymentOrder
{
    private readonly int $id;

    private readonly CreditorAccount $creditorAccount;

    private readonly int $amount;

    private readonly string $token;

    private readonly DateTime $dateCreated;

    private int $status;

    public function __construct(CreditorAccount $creditorAccount, int $amount)
    {
        $this->status = PaymentOrderStatus::PENDING_CONSENT->value;
        $this->dateCreated = new DateTime('now');
        $this->token = md5(microtime(true).mt_Rand());
        $this->creditorAccount = $creditorAccount;
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
