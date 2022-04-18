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

    public function create(CreatePaymentOrderRequest $request): string
    {
        $creditorAccount = new CreditorAccount($request->creditorIban, $request->creditorName);
        $paymentOrder = new PaymentOrder($creditorAccount, $request->amount, $request->bank);
        $this->paymentOrderRepository->save($paymentOrder);

        return $paymentOrder->getToken();
    }
}
