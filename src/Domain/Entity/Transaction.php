<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\Psu;

class Transaction
{
    private readonly int $id;

    private DateTime $dateCreated;

    private readonly PaymentOrder $paymentOrder;

    private readonly Psu $psu;

    private readonly BankAccount $bankAccount;

    private ?string $scaRedirectUrl = null;

    private ?string $transactionId;

    public function __construct(PaymentOrder $paymentOrder, Psu $psu, BankAccount $bankAccount)
    {
        $this->paymentOrder = $paymentOrder;
        $this->psu = $psu;
        $this->bankAccount = $bankAccount;
        $this->dateCreated = new DateTime('now');
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function hasScaInfo(): bool
    {
        return is_string($this->scaRedirectUrl)
            && is_string($this->transactionId);
    }

    public function updateScaInfo(string $scaRedirectUrl, string $transactionId): void
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
        $this->transactionId = $transactionId;
    }

    public function getBankCode(): string
    {
        return $this->bankAccount->getBankCode();
    }

    public function getAmount(): int
    {
        return $this->paymentOrder->getAmount();
    }

    public function getScaRedirectUrl(): ?string
    {
        return $this->scaRedirectUrl;
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getPsuIp(): string
    {
        return $this->psu->getIpAddress();
    }

    public function getCreditorName(): string
    {
        return $this->bankAccount->getAccountHolderName();
    }

    public function getCreditorIban(): string
    {
        return $this->bankAccount->getIban();
    }

    public function getPaymentOrderToken(): string
    {
        return $this->paymentOrder->getToken();
    }
}
