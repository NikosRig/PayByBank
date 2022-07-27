<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class AccessTokenState
{
    public readonly string $token;

    public readonly string $merchantId;

    public readonly DateTime $dateCreated;

    public readonly DateTime $expirationDate;

    public readonly bool $isUsed;

    public readonly string $id;

    public function __construct(
        string $token,
        string $mid,
        DateTime $dateCreated,
        DateTime $expirationDate,
        bool $isUsed,
        string $id
    ) {
        $this->token = $token;
        $this->merchantId = $mid;
        $this->dateCreated = $dateCreated;
        $this->expirationDate = $expirationDate;
        $this->isUsed = $isUsed;
        $this->id = $id;
    }
}
