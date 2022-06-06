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

    private readonly int $amount;

    private DateTime $dateCreated;

    public function __construct(int $amount)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->status = PaymentOrderStatus::PENDING;
        $this->amount = $amount;
        $this->dateCreated = new DateTime('now');
    }

    public function getState(): PaymentOrderState
    {
        return new PaymentOrderState(
            $this->dateCreated,
            $this->status,
            $this->token,
            $this->amount
        );
    }

    public static function fromState(PaymentOrderState $paymentOrderState): PaymentOrder
    {
        $self = new self($paymentOrderState->amount);
        $self->status = $paymentOrderState->status;
        $self->token = $paymentOrderState->token;

        return $self;
    }

    public function canBeAuthorized(): bool
    {
        return $this->status->isPending();
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
