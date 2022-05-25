<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\JwtState;

class Jwt
{
    private readonly string $mid;

    private readonly string $token;

    private readonly DateTime $dateCreated;

    private bool $isUsed;

    public function __construct(string $mid, string $token)
    {
        $this->mid = $mid;
        $this->token = $token;
        $this->dateCreated = new DateTime('now');
        $this->isUsed = false;
    }

    public static function fromState(JwtState $jwtState): Jwt
    {
        $jwt = new self($jwtState->mid, $jwtState->token);
        $jwt->dateCreated = $jwtState->dateCreated;
        $jwt->isUsed = $jwtState->isUsed;

        return $jwt;
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
