<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

class CreateMerchantRequest
{
    public readonly string $username;

    public readonly string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
