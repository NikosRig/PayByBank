<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use InvalidArgumentException;
use PayByBank\Domain\Entity\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transaction): void;

    /**
     * @throws InvalidArgumentException
     */
    public function findByTransactionId(string $transactionId): Transaction;
}
