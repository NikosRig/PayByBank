<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class MerchantState
{
    public readonly string $username;

    public readonly string $password;

    public readonly DateTime $dateCreated;

    public function __construct(string $username, string $password, DateTime $dateCreated)
    {
        $this->username = $username;
        $this->password = $password;
        $this->dateCreated = $dateCreated;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'dateCreated' => $this->dateCreated->format('Y-m-d H:i:s')
        ];
    }
}
