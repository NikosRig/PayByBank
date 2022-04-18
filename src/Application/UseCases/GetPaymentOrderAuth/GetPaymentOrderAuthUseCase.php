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
    public function get(string $paymentOrderToken): GetPaymentOrderAuthResponse
    {
        $paymentOrder = $this->repository->findByToken($paymentOrderToken);

        if (!$paymentOrder || !$paymentOrder->canBeAuthorized()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }

        return new GetPaymentOrderAuthResponse(
            $paymentOrder->getBank()
        );
    }
}
