<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\XS2A\DTO;

class XS2ASepaPaymentRequest
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly string $debtorIban;

    public readonly float $amount;

    public readonly string $psuIp;

    public readonly string $transactionId;

    public function __construct(
        string $creditorIban,
        string $creditorName,
        string $debtorIban,
        float $amount,
        string $psuIp,
        string $transactionId
    ) {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->debtorIban = $debtorIban;
        $this->amount = $amount;
        $this->psuIp = $psuIp;
        $this->transactionId = $transactionId;
    }
}
