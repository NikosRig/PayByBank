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

    private readonly string $bankName;

    public function __construct(CreditorAccount $creditorAccount, int $amount, string $bankName)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->status = PaymentOrderStatus::PENDING;
        $this->creditorAccount = $creditorAccount;
        $this->amount = $amount;
        $this->dateCreated = new DateTime('now');
        $this->bankName = $bankName;
    }

    public static function fromState(PaymentOrderState $paymentOrderState): PaymentOrder
    {
        $self = new self(
            $paymentOrderState->creditorAccount,
            $paymentOrderState->amount,
            $paymentOrderState->bankName
        );

        $self->status = $paymentOrderState->status;
        $self->token = $paymentOrderState->token;

        return $self;
    }

    public function canBeAuthorized(): bool
    {
        return $this->status->isPending();
    }

    /**
     * @return string
     */
    public function getBankName(): string
    {
        return $this->bankName;
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
