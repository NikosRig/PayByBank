<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use PayByBank\Domain\ValueObjects\AccountState;

class Account
{
    private readonly ?string $id;

    private readonly string $iban;

    private readonly string $accountHolderName;

    public function __construct(string $iban, string $accountHolderName, string $merchantId)
    {
        $this->iban = $iban;
        $this->accountHolderName = $accountHolderName;
        $this->merchantId = $merchantId;
    }

    public static function fromState(AccountState $accountState): Account
    {
        $account = new self(
            $accountState->iban,
            $accountState->accountHolderName,
            $accountState->merchantId
        );
        $account->id = $accountState->id;

        return $account;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    private readonly string $merchantId;

    public function getIban(): string
    {
        return $this->iban;
    }
}
