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

    public readonly ?string $id;

    public function __construct(
        string $mid,
        string $firstName,
        string $lastName,
        DateTime $dateCreated,
        ?string $id
    ) {
        $this->mid = $mid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateCreated = $dateCreated;
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mid' => $this->mid,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s')
        ];
    }
}
