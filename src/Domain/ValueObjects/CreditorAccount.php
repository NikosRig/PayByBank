<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class CreditorAccount
{
    public readonly string $iban;

    public readonly string $creditorName;

    public function __construct(string $iban, string $creditorName)
    {
        $this->iban = $iban;
        $this->creditorName = $creditorName;
    }

    public function toArray(): array
    {
        return [
          'iban' => $this->iban,
          'creditorName' => $this->creditorName
        ];
    }
}
