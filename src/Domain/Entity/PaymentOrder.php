<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\PaymentOrderState;
use PayByBank\Domain\ValueObjects\PaymentOrderStatus;

final class PaymentOrder
{
    private readonly string $id;

    private string $token;

    private PaymentOrderStatus $status;

    private readonly int $amount;

    private DateTime $dateCreated;

    private readonly string $merchantId;

    private readonly string $description;

    public static function fromState(PaymentOrderState $state): PaymentOrder
    {
        $self = new self(
            $state->amount,
            $state->merchantId,
            $state->description
        );
        $self->status = $state->status;
        $self->token = $state->token;

        return $self;
    }

    public function __construct(int $amount, string $merchantId, string $description)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(24));
        $this->status = PaymentOrderStatus::CREATED;
        $this->amount = $amount;
        $this->merchantId = $merchantId;
        $this->dateCreated = new DateTime('now');
        $this->description = $description;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
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

    public function hasExpired(): bool
    {
        $expirationDate = $this->dateCreated->modify('+15 min');

        return !$this->status->isStatusCreated()
            || $expirationDate < new DateTime('now');
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function markPaid(): void
    {
        $this->status = PaymentOrderStatus::PAID;
    }
}
