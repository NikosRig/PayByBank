<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

class Merchant
{
    private readonly int $id;

    private readonly string $username;

    private readonly string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}
