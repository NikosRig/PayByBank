<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA\DTO;

class RegisterSepaPaymentRequest
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly float $amount;

    public function __construct(
        string $creditorIban,
        string $creditorName,
        float $amount
    ) {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
    }
}