<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\Checkout;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethod;

class CheckoutPresenter
{
    public readonly array $bankCodes;

    public readonly string $amount;

    public readonly string $description;

    public string $paymentOrderToken;

    public function present(
        array $paymentMethods,
        int $paymentOrderAmount,
        string $description,
        string $paymentOrderToken
    ): void {
        $this->amount = number_format((float) $paymentOrderAmount / 100, 2);
        $this->paymentOrderToken = htmlspecialchars($paymentOrderToken);
        $this->description = $description;
        foreach ($paymentMethods as $paymentMethod) {
            $this->bankCodes[] = $paymentMethod->getBankCode();
        }
    }
}
