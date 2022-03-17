<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\IPaymentOrderStoreRepository;

class CreatePaymentOrderUseCase
{
    private IPaymentOrderStoreRepository $orderPersistenceRepository;

    public function __construct(IPaymentOrderStoreRepository $orderPersistenceRepository)
    {
        $this->orderPersistenceRepository = $orderPersistenceRepository;
    }

    public function __invoke(string $creditorIban, string $creditorName, int $amount): string
    {
        $order = new PaymentOrder($creditorIban, $creditorName, $amount);
        $this->orderPersistenceRepository->persist($order);

        return $order->token;
    }
}
