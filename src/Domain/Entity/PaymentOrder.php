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
        $this->status = PaymentOrderStatus::CREATED;
        $this->amount = $amount;
        $this->merchantId = $merchantId;
        $this->dateCreated = new DateTime('now');
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function markAuthorized(): void
    {
        $this->status = PaymentOrderStatus::AUTHORIZED;
    }

    public function isAuthorized(): bool
    {
        return $this->status->isStatusAuthorized();
    }
}
