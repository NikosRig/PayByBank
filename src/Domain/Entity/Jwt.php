<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;

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

    public function markUsed(): void
    {
        $this->isUsed = true;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }
}
