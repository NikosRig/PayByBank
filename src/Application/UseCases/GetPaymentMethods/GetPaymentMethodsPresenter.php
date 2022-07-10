<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentMethods;

use PayByBank\Domain\PaymentMethod;

class GetPaymentMethodsPresenter
{
    /**
     * @var PaymentMethod[]
     */
    public readonly array $paymentMethods;

    public function present(array $paymentMethods): void
    {
        $this->paymentMethods = $paymentMethods;
    }
}
