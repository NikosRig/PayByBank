<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Banks;

use InvalidArgumentException;
use PayByBank\Domain\Http\Banks\Bank;
use PayByBank\Domain\Http\Banks\BankResolver;

class ActiveBankResolver implements BankResolver
{
    private array $banks;

    public function __construct(array $banks)
    {
        $this->banks = $banks;
    }

    public function resolveWithName(string $name): Bank
    {
        foreach ($this->banks as $bankName => $bank) {
            if (strtolower($bankName) === strtolower($name)) {
                return $bank;
            }
        }
        throw new InvalidArgumentException('Invalid bank name provided.');
    }
}
