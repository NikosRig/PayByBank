<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use PayByBank\Domain\ValueObjects\BankAccountState;

class BankAccount
{
    private ?string $id = null;

    private readonly string $iban;

    private readonly string $accountHolderName;

    private readonly string $bankCode;

    public function __construct(
        string $iban,
        string $accountHolderName,
        string $merchantId,
        string $bankCode
    ) {
        $this->iban = $iban;
        $this->accountHolderName = $accountHolderName;
        $this->merchantId = $merchantId;
        $this->bankCode = $bankCode;
    }

    public static function fromState(BankAccountState $state): BankAccount
    {
        $self = new self(
            $state->iban,
            $state->accountHolderName,
            $state->merchantId,
            $state->bankCode
        );
        $self->id = $state->id;

        return $self;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
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
