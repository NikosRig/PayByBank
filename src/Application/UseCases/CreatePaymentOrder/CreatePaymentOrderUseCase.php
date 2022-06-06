<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class CreatePaymentOrderUseCase
{
    private PaymentOrderRepository $paymentOrderRepository;

    public function __construct(PaymentOrderRepository $paymentOrderRepository)
    {
        $this->paymentOrderRepository = $paymentOrderRepository;
    }

    public function create(CreatePaymentOrderRequest $request, CreatePaymentOrderPresenter $presenter): void
    {
        $paymentOrder = new PaymentOrder($request->amount);
        $this->paymentOrderRepository->save($paymentOrder);

        $presenter->present($paymentOrder->getToken());
    }
}
