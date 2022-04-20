<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderPresenter
{
    private readonly string $paymentOrderToken;

    public function present(string $paymentOrderToken): void
    {
        $this->paymentOrderToken = $paymentOrderToken;
    }

    public function getPaymentOrderToken(): string
    {
        return $this->paymentOrderToken;
    }
}
