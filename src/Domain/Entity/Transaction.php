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

    public function __construct(PaymentOrder $paymentOrder, Psu $psu, BankAccount $bankAccount)
    {
        $this->paymentOrder = $paymentOrder;
        $this->psu = $psu;
        $this->bankAccount = $bankAccount;
        $this->dateCreated = new DateTime('now');
    }

    public function hasScaInfo(): bool
    {
        return is_string($this->scaRedirectUrl);
    }

    public function updateScaInfo(string $scaRedirectUrl): void
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
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

    public function getPaymentOrderId(): string
    {
        return $this->paymentOrder->getId();
    }
}
