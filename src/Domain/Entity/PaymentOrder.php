<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PayByBank\Domain\ValueObjects\PaymentOrderState;

final class PaymentOrder
{
    private readonly int $id;

    private readonly string $token;

    private readonly CreditorAccount $creditorAccount;

    private readonly PaymentOrderState $state;

    private readonly int $amount;

    private readonly DateTime $dateCreated;

    private readonly string $bank;

    public function __construct(CreditorAccount $creditorAccount, int $amount, string $bank)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->state = PaymentOrderState::PENDING_CONSENT;
        $this->creditorAccount = $creditorAccount;
        $this->amount = $amount;
        $this->dateCreated = new DateTime('now');
        $this->bank = $bank;
    }

    /**
     * @return string
     */
    public function getBank(): string
    {
        return $this->bank;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return PaymentOrderState
     */
    public function getState(): PaymentOrderState
    {
        return $this->state;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return CreditorAccount
     */
    public function getCreditorAccount(): CreditorAccount
    {
        return $this->creditorAccount;
    }
}
