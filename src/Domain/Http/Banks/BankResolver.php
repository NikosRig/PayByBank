<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\Banks;

interface BankResolver
{
    public function resolveWithName(string $bankName): Bank;
}
