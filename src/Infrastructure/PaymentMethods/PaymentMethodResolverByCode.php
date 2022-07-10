<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\PaymentMethods;

use Exception;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\PaymentMethod;
use PayByBank\Domain\PaymentMethodResolver;

class PaymentMethodResolverByCode implements PaymentMethodResolver
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
    public function resolve(BankAccount $bankAccount): PaymentMethod
    {
        foreach ($this->paymentMethods as $paymentMethod) {
            if ($bankAccount->getBankCode() === $paymentMethod->getBankCode()) {
                return $paymentMethod;
            }
        }

        throw new Exception('Payment method cannot be found.');
    }
}
