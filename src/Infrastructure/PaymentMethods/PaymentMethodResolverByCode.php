<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\PaymentMethods;

use Exception;
use PayByBank\Domain\Entity\Transaction;
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
    public function resolveWithBankCode(string $bankCode): PaymentMethod
    {
        foreach ($this->paymentMethods as $paymentMethod) {
            if ($bankCode == $paymentMethod->getBankCode()) {
                return $paymentMethod;
            }
        }

        throw new Exception('Payment method cannot be found.');
    }

    public function resolve(Transaction $transaction): PaymentMethod
    {
        $bankCode = $transaction->getBankCode();
    }
}
