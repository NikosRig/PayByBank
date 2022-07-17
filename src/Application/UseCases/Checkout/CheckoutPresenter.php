<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\Checkout;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethod;

class CheckoutPresenter
{
    public readonly array $bankCodes;

    public readonly float $amount;

    public function present(array $paymentMethods, int $paymentOrderAmount): void
    {
        $this->amount = (float) number_format($paymentOrderAmount / 100);

        foreach ($paymentMethods as $paymentMethod) {
            $this->bankCodes[] = $paymentMethod->getBankCode();
        }
    }
}
