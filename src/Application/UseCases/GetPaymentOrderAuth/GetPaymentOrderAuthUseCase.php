<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderAuth;

use InvalidArgumentException;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class GetPaymentOrderAuthUseCase
{
    private PaymentOrderRepository $repository;

    public function __construct(PaymentOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(GetPaymentOrderAuthRequest $request, GetPaymentOrderAuthPresenter $presenter): void
    {
        $paymentOrder = $this->repository->findByToken($request->paymentOrderToken);

        if (!$paymentOrder || !$paymentOrder->canBeAuthorized()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }

        $presenter->present($paymentOrder->getBank());
    }
}
