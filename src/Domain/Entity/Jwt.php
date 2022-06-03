<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\JwtState;

class Jwt
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

    public static function fromState(JwtState $jwtState): Jwt
    {
        $jwt = new self($jwtState->mid, $jwtState->token, $jwtState->expirationDate);
        $jwt->dateCreated = $jwtState->dateCreated;
        $jwt->isUsed = $jwtState->isUsed;

        return $jwt;
    }

    public function getState(): JwtState
    {
        return new JwtState(
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
