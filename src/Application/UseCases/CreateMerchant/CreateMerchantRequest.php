<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

class CreateMerchantRequest
{
    public readonly string $firstName;

    public readonly string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
