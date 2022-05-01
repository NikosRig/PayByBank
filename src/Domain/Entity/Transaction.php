<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use PayByBank\Domain\ValueObjects\Psu;

class Transaction
{
    private readonly PaymentOrder $paymentOrder;

    private readonly Psu $psu;

    public function __construct(PaymentOrder $paymentOrder, Psu $psu)
    {
        $this->paymentOrder = $paymentOrder;
        $this->psu = $psu;
    }

    public function getPsu(): Psu
    {
        return $this->psu;
    }

    public function getPaymentOrder(): PaymentOrder
    {
        return $this->paymentOrder;
    }
}
