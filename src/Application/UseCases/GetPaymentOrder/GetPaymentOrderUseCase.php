<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrder;

use InvalidArgumentException;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class GetPaymentOrderUseCase
{
    private PaymentOrderRepository $repository;

    public function __construct(PaymentOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(GetPaymentOrderRequest $request, GetPaymentOrderPresenter $presenter): void
    {
        $paymentOrder = $this->repository->findByToken($request->paymentOrderToken);

        if (!$paymentOrder || !$paymentOrder->isStatusPending()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }

        $presenter->present(
            $paymentOrder->getToken(),
            $paymentOrder->getAmount()
        );
    }
}
