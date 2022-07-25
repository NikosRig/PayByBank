<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use InvalidArgumentException;
use PayByBank\Domain\Entity\BankAccount;

interface BankAccountRepository
{
    public function findByBankCodeAndMerchantId(string $bankCode, string $merchantId): ?BankAccount;

    public function save(BankAccount $bankAccount): void;

    public function findAllByMerchantId(string $merchantId): ?array;

    /**
     * @throws InvalidArgumentException
     */
    public function findById(string $id): BankAccount;
}
