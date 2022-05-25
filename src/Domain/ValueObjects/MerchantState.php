<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class MerchantState
{
    public readonly string $mid;

    public readonly string $firstName;

    public readonly string $lastName;

    public readonly DateTime $dateCreated;

    public function __construct(string $mid, string $firstName, string $lastName, DateTime $dateCreated)
    {
        $this->mid = $mid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateCreated = $dateCreated;
    }

    public function toArray(): array
    {
        return [
            'mid' => $this->mid,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s')
        ];
    }
}
