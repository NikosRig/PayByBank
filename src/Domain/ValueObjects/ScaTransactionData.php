<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

class ScaTransactionData
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly int $amount;

    public ?string $transactionId;

    public ?string $scaRedirectUrl;

    public function __construct(string $creditorIban, string $creditorName, int $amount)
    {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
    }

    public function addScaInfo(string $scaRedirectUrl, string $transactionId): void
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
        $this->transactionId = $transactionId;
    }
}
