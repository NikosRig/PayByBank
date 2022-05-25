<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class JwtState
{
    public readonly string $token;

    public readonly string $mid;

    public readonly DateTime $dateCreated;

    public readonly bool $isUsed;

    public function __construct(
        string $token,
        string $mid,
        DateTime $dateCreated,
        bool $isUsed
    ) {
        $this->token = $token;
        $this->mid = $mid;
        $this->dateCreated = $dateCreated;
        $this->isUsed = $isUsed;
    }

    public function toArray(): array
    {
        return [
          'token' => $this->token,
          'mid' => $this->mid,
          'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s'),
          'isUsed' => $this->isUsed
        ];
    }
}
