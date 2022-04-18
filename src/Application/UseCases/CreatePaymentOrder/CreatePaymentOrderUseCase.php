<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\ValueObjects\CreditorAccount;

class CreatePaymentOrderUseCase
{
    private PaymentOrderRepository $paymentOrderRepository;

    public function __construct(PaymentOrderRepository $paymentOrderRepository)
    {
        $this->paymentOrderRepository = $paymentOrderRepository;
    }

    public function create(string $creditorIban, string $creditorName, int $amount, string $bank): string
    {
        $creditorAccount = new CreditorAccount($creditorIban, $creditorName);
        $paymentOrder = new PaymentOrder($creditorAccount, $amount, $bank);
        $this->paymentOrderRepository->save($paymentOrder);

        return $paymentOrder->getToken();
    }
}
