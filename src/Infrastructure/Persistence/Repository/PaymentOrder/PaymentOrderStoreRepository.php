<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository\PaymentOrder;

use MongoDB\Collection;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Infrastructure\Persistence\Database\MongoDB;

class PaymentOrderStoreRepository
{
    private Collection $collection;

    public function __construct(MongoDB $mongo)
    {
        $this->collection = $mongo->selectCollection('paymentOrders');
    }

    public function store(PaymentOrder $paymentOrder): void
    {
        $this->collection->insertOne([
            'token' => $paymentOrder->getToken(),
            'state' => $paymentOrder->getState()->name,
            'dateCreated' => $paymentOrder->getDateCreated()->format('Y-m-d H:i:s'),
            'amount' => $paymentOrder->getAmount(),
            'creditorAccount' => $paymentOrder->getCreditorAccount()->toArray()
        ]);
    }
}
