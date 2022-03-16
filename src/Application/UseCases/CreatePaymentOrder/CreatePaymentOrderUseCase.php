<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\IPaymentOrderPersistenceRepository;

class CreatePaymentOrderUseCase
{
    private IPaymentOrderPersistenceRepository $orderPersistenceRepository;

    public function __construct(IPaymentOrderPersistenceRepository $orderPersistenceRepository)
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
