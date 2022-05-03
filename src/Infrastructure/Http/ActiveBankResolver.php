<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http;

use InvalidArgumentException;
use PayByBank\Domain\Http\BankResolver;
use PayByBank\Domain\Http\Banks\Bank;

class ActiveBankResolver implements BankResolver
{
    private array $bankProviders;

    public function __construct(array $bankProviders)
    {
        $this->bankProviders = $bankProviders;
    }

    public function resolveWithName(string $name): Bank
    {
        foreach ($this->bankProviders as $bankProviderName => $bankProvider) {
            if (strtolower($bankProviderName) === strtolower($name)) {
                return $bankProvider;
            }
        }
        throw new InvalidArgumentException('Invalid bank provider.');
    }
}
