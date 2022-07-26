<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use InvalidArgumentException;
use MongoDB\Collection;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\ValueObjects\TransactionState;
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
            'bankAccountId' => $transaction->getBankAccountId(),
            'paymentOrderToken' => $transaction->getPaymentOrderToken(),
            'scaRedirectUrl' => $transaction->getScaRedirectUrl(),
            'transactionId' => $transaction->getTransactionId(),
            'psuIp' => $transaction->getPsuIp(),
            'dateCreated' => $transaction->getDateCreated()->format('Y-m-d H:i:s'),
            'bankData' => $transaction->getBankData()
        ]);
    }

    public function findByTransactionId(string $transactionId): Transaction
    {
        if (!$transaction = $this->collection->findOne(['transactionId' => $transactionId])) {
            throw new InvalidArgumentException('Transaction cannot be found');
        }

        $state = new TransactionState(
            $transaction->_id->__toString(),
            DateTime::createFromFormat('Y-m-d H:i:s', $transaction->dateCreated),
            $transaction->bankAccountId,
            $transaction->paymentOrderToken,
            $transaction->scaRedirectUrl,
            $transaction->transactionId,
            $transaction->psuIp,
            $transaction->bankData
        );

        return Transaction::fromState($state);
    }
}
