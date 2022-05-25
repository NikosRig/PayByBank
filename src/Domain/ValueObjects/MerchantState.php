<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class MerchantState
{
    public readonly string $mid;

    public readonly string $merchantName;

    public readonly DateTime $dateCreated;

    public function __construct(string $mid, string $merchantName, DateTime $dateCreated)
    {
        $this->mid = $mid;
        $this->merchantName = $merchantName;
        $this->dateCreated = $dateCreated;
    }

    public function toArray(): array
    {
        return [
            'mid' => $this->mid,
            'merchantName' => $this->merchantName,
            'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s')
        ];
    }
}
