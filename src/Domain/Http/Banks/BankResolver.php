<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\Banks;

use InvalidArgumentException;

interface BankResolver
{
    /**
     * @throws InvalidArgumentException
     */
    public function resolveWithName(string $name): Bank;
}
