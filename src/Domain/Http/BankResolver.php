<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http;

use InvalidArgumentException;
use PayByBank\Domain\Http\Banks\Bank;

interface BankResolver
{
    /**
     * @throws InvalidArgumentException
     */
    public function resolveWithName(string $name): Bank;
}
