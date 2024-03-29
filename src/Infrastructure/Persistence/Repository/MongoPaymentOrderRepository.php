<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\ValueObjects\PaymentOrderState;
use PayByBank\Domain\ValueObjects\PaymentOrderStatus;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoPaymentOrderRepository implements PaymentOrderRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('payment_orders');
    }

    public function findByToken(string $paymentOrderToken): ?PaymentOrder
    {
        if (!$paymentOrder = $this->collection->findOne(['token' => $paymentOrderToken])) {
            return null;
        }

        $state = new PaymentOrderState(
            DateTime::createFromFormat('Y-m-d H:i:s', $paymentOrder->dateCreated),
            PaymentOrderStatus::from($paymentOrder->status),
            $paymentOrder->token,
            $paymentOrder->amount,
            $paymentOrder->merchantId,
            $paymentOrder->description,
            $paymentOrder->_id->__toString()
        );

        return PaymentOrder::fromState($state);
    }

    public function save(PaymentOrder $paymentOrder): void
    {
        $this->collection->insertOne([
            'token' => $paymentOrder->getToken(),
            'merchantId' => $paymentOrder->getMerchantId(),
            'status' => $paymentOrder->getStatus()->value,
            'dateCreated' => $paymentOrder->getDateCreated()->format('Y-m-d H:i:s'),
            'amount' => $paymentOrder->getAmount(),
            'description' => $paymentOrder->getDescription()
        ]);
    }

    public function update(PaymentOrder $paymentOrder): void
    {
        $this->collection->updateOne(
            ['_id' => new ObjectId($paymentOrder->getId())],
            ['$set' => $this->mapToCollectionArray($paymentOrder)]
        );
    }

    private function mapToCollectionArray(PaymentOrder $paymentOrder): array
    {
        return [
            'token' => $paymentOrder->getToken(),
            'merchantId' => $paymentOrder->getMerchantId(),
            'status' => $paymentOrder->getStatus()->value,
            'dateCreated' => $paymentOrder->getDateCreated()->format('Y-m-d H:i:s'),
            'amount' => $paymentOrder->getAmount(),
            'description' => $paymentOrder->getDescription()
        ];
    }
}
