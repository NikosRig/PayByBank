<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use MongoDB\Collection;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PayByBank\Domain\ValueObjects\PaymentOrderState;
use PayByBank\Domain\ValueObjects\PaymentOrderStatus;
use PayByBank\Infrastructure\Persistence\Database\MongoDB;

class PaymentOrderRepository implements \PayByBank\Domain\Repository\PaymentOrderRepository
{
    private Collection $collection;

    public function __construct(MongoDB $mongo)
    {
        $this->collection = $mongo->selectCollection('paymentOrders');
    }

    public function findByToken(string $paymentOrderToken): ?PaymentOrder
    {
        if (!$paymentOrder = $this->collection->findOne(['token' => $paymentOrderToken])) {
            return null;
        }

        $creditorAccount = new CreditorAccount(
            $paymentOrder->creditorAccount->iban,
            $paymentOrder->creditorAccount->creditorName
        );

        $state = new PaymentOrderState(
            DateTime::createFromFormat('Y-m-d H:i:s', $paymentOrder->dateCreated),
            PaymentOrderStatus::from($paymentOrder->status),
            $paymentOrder->token,
            $paymentOrder->amount,
            $paymentOrder->bank,
            $creditorAccount
        );

        return PaymentOrder::fromState($state);
    }

    public function save(PaymentOrder $paymentOrder): void
    {
        $this->collection->insertOne([
             'token' => $paymentOrder->getToken(),
             'status' => $paymentOrder->getStatus(),
             'dateCreated' => $paymentOrder->getDateCreated()->format('Y-m-d H:i:s'),
             'amount' => $paymentOrder->getAmount(),
             'creditorAccount' => $paymentOrder->getCreditorAccount()->toArray(),
             'bank' => $paymentOrder->getBank()
        ]);
    }
}
