<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class AccountState
{
    public readonly string $id;

    public readonly string $iban;

    public readonly string $accountHolderName;

    public readonly string $merchantId;

    public function __construct(
        string $iban,
        string $accountHolderName,
        string $merchantId,
        string $id,
    ) {
        $this->iban = $iban;
        $this->accountHolderName = $accountHolderName;
        $this->merchantId = $merchantId;
        $this->id = $id;
    }
}
