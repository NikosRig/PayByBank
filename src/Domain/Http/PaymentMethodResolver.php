<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http;

use Exception;

class PaymentMethodResolver
{
    /**
     * @var PaymentMethod[]
     */
    private array $paymentMethods;

    public function __construct(PaymentMethod ...$paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;
    }

    /**
     * @throws Exception
     */
    public function resolveWithBankCode(string $bankCode): PaymentMethod
    {
        foreach ($this->paymentMethods as $paymentMethod) {
            if ($bankCode == $paymentMethod->getBankCode()) {
                return $paymentMethod;
            }
        }

        throw new Exception('Payment method cannot be found.');
    }
}
