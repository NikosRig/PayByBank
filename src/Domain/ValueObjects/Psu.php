<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class Psu
{
    private readonly string $ipAddress;

    public function __construct(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }
}
