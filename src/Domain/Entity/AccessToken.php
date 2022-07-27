<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\AccessTokenState;

class AccessToken
{
    private ?string $id = null;

    private readonly string $merchantId;

    private readonly string $token;

    private DateTime $dateCreated;

    private DateTime $expirationDate;

    private bool $isUsed;

    public static function fromState(AccessTokenState $state): AccessToken
    {
        $self = new self($state->merchantId, $state->token, $state->expirationDate);
        $self->dateCreated = $state->dateCreated;
        $self->isUsed = $state->isUsed;
        $self->id = $state->id;

        return $self;
    }

    public function __construct(string $merchantId, string $token, DateTime $expirationDate)
    {
        $this->merchantId = $merchantId;
        $this->token = $token;
        $this->dateCreated = new DateTime('now');
        $this->expirationDate = $expirationDate;
        $this->isUsed = false;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function markUsed(): void
    {
        $this->isUsed = true;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }
}
