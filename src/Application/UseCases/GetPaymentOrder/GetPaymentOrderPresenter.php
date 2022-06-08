<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrder;

class GetPaymentOrderPresenter
{
    public readonly ?string $paymentOrderToken;

    public readonly ?int $amount;

    public function present(string $paymentOrderToken, int $amount): void
    {
        $this->paymentOrderToken = $paymentOrderToken;
        $this->amount = $amount;
    }
}
