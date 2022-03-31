<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class CreditorAccount
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public function __construct(string $creditorIban, string $creditorName)
    {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
    }

    public function toArray(): array
    {
        return [
          'creditorIban' => $this->creditorIban,
          'creditorName' => $this->creditorName
        ];
    }
}
