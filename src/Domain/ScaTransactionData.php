<?php

declare(strict_types=1);

namespace PayByBank\Domain;

class ScaTransactionData
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly int $amount;

    public ?string $transactionId;

    public ?string $scaRedirectUrl;

    public readonly array $bankData;

    public function __construct(string $creditorIban, string $creditorName, int $amount)
    {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
    }

    public function addScaInfo(string $scaRedirectUrl, string $transactionId, array $bankData = []): void
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
        $this->transactionId = $transactionId;
        $this->bankData = $bankData;
    }
}
