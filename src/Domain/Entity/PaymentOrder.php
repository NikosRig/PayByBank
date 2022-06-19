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

    private readonly string $merchantId;

    public static function fromState(PaymentOrderState $state): PaymentOrder
    {
        $self = new self(
            $state->amount,
            $state->merchantId
        );
        $self->status = $state->status;
        $self->token = $state->token;

        return $self;
    }

    public function __construct(int $amount, string $merchantId)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->status = PaymentOrderStatus::PENDING;
        $this->amount = $amount;
        $this->merchantId = $merchantId;
        $this->dateCreated = new DateTime('now');
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getStatus(): PaymentOrderStatus
    {
        return $this->status;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function isStatusPending(): bool
    {
        return $this->status->isPending();
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
