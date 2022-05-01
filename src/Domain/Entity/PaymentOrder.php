<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PayByBank\Domain\ValueObjects\PaymentOrderState;
use PayByBank\Domain\ValueObjects\PaymentOrderStatus;
use PayByBank\Domain\ValueObjects\Psu;

final class PaymentOrder
{
    private readonly int $id;

    private string $token;

    private PaymentOrderStatus $status;

    private readonly CreditorAccount $creditorAccount;

    private readonly int $amount;

    private DateTime $dateCreated;

    private readonly string $bank;

    private ?Psu $psu;

    public function __construct(CreditorAccount $creditorAccount, int $amount, string $bank)
    {
        $this->psu = null;
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->status = PaymentOrderStatus::PENDING;
        $this->creditorAccount = $creditorAccount;
        $this->amount = $amount;
        $this->dateCreated = new DateTime('now');
        $this->bank = $bank;
    }

    public static function fromState(PaymentOrderState $paymentOrderState): PaymentOrder
    {
        $self = new self(
            $paymentOrderState->creditorAccount,
            $paymentOrderState->amount,
            $paymentOrderState->bank
        );

        $self->status = $paymentOrderState->status;
        $self->token = $paymentOrderState->token;
        $self->psu = $paymentOrderState->psu;

        return $self;
    }

    public function getPsu(): ?Psu
    {
        return $this->psu;
    }

    public function canBeAuthorized(): bool
    {
        return $this->status->isPending();
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
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status->value;
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
