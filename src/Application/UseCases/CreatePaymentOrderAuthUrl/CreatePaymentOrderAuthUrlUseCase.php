<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class CreatePaymentOrderAuthUrlUseCase
{
    private PaymentOrderRepository $repository;

    public function __construct(PaymentOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(CreatePaymentOrderAuthUrlRequest $request, CreatePaymentOrderPresenter $presenter): void
    {
        $paymentOrder = $this->repository->findByToken($request->paymentOrderToken);

        if (!$paymentOrder || !$paymentOrder->canBeAuthorized()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }
    }
}
