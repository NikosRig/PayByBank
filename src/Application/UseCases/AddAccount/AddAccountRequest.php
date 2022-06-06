<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\AddAccount;

class AddAccountRequest
{
    public readonly string $iban;

    public readonly string $accountHolderName;

    public readonly string $bankCode;

    public readonly string $mid;

    public function __construct(
        string $iban,
        string $accountHolderName,
        string $bankCode,
        string $mid
    ) {
        $this->iban = $iban;
        $this->accountHolderName = $accountHolderName;
        $this->bankCode = $bankCode;
        $this->mid = $mid;
    }
}
