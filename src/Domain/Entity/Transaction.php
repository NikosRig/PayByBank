<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\Psu;

class Transaction
{
    private readonly string $id;

    private readonly DateTime $dateCreated;

    private readonly string $scaRedirectUrl;

    private readonly string $transactionId;

    private readonly string $paymentOrderToken;

    private readonly string $psuIp;

    private readonly string $bankCode;

    public function __construct(
        string $paymentOrderToken,
        string $bankCode,
        string $psuIp,
        string $transactionId,
        string $scaRedirectUrl
    ) {
        $this->paymentOrderToken = $paymentOrderToken;
        $this->bankCode = $bankCode;
        $this->psuIp = $psuIp;
        $this->transactionId = $transactionId;
        $this->scaRedirectUrl = $scaRedirectUrl;
        $this->dateCreated = new DateTime('now');
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
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
        return $this->psuIp;
    }

    public function getPaymentOrderToken(): string
    {
        return $this->paymentOrderToken;
    }
}
