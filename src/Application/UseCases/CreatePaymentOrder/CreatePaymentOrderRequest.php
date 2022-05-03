<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderRequest
{
    public readonly string $creditorIban;

    public readonly string $creditorName;

    public readonly int $amount;

    public readonly string $bankName;

    public function __construct(
        string $creditorIban,
        string $creditorName,
        int $amount,
        string $bankName
    ) {
        $this->creditorIban = $creditorIban;
        $this->creditorName = $creditorName;
        $this->amount = $amount;
        $this->bankName = $bankName;
    }
}
