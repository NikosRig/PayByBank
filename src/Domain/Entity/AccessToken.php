<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\AccessTokenState;

class AccessToken
{
    private readonly string $mid;

    private readonly string $token;

    private DateTime $dateCreated;

    private DateTime $expirationDate;

    private bool $isUsed;

    public function __construct(string $mid, string $token, DateTime $expirationDate)
    {
        $this->mid = $mid;
        $this->token = $token;
        $this->dateCreated = new DateTime('now');
        $this->expirationDate = $expirationDate;
        $this->isUsed = false;
    }

    public static function fromState(AccessTokenState $state): AccessToken
    {
        $self = new self($state->mid, $state->token, $state->expirationDate);
        $self->dateCreated = $state->dateCreated;
        $self->isUsed = $state->isUsed;

        return $self;
    }

    public function getState(): AccessTokenState
    {
        return new AccessTokenState(
            $this->token,
            $this->mid,
            $this->dateCreated,
            $this->expirationDate,
            $this->isUsed
        );
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
