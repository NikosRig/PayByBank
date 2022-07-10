<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentMethods;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethod;

class GetPaymentMethodsPresenter
{
    public readonly array $bankCodes;

    public function present(array $paymentMethods): void
    {
        foreach ($paymentMethods as $paymentMethod) {
            $this->bankCodes[] = $paymentMethod->getBankCode();
        }
    }
}
