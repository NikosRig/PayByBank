<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use MongoDB\Collection;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoTransactionRepository implements TransactionRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('transactions');
    }

    public function save(Transaction $transaction): void
    {
        $this->collection->insertOne([
            'bankCode' => $transaction->getBankCode(),
            'paymentOrderToken' => $transaction->getPaymentOrderToken(),
            'scaRedirectUrl' => $transaction->getScaRedirectUrl(),
            'transactionId' => $transaction->getTransactionId(),
            'psuIp' => $transaction->getPsuIp(),
            'dateCreated' => $transaction->getDateCreated()->format('Y-m-d H:i:s')
        ]);
    }
}
