<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class Psu
{
    public readonly ?string $ipAddress;

    public function __construct(?string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function toArray(): array
    {
        return [
          'ip_address' => $this->ipAddress
        ];
    }
}
