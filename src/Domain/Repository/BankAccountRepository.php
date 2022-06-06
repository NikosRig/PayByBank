<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\BankAccount;

interface BankAccountRepository
{
    public function findByBankCodeAndMerchantId(string $bankCode, string $merchantId): ?BankAccount;

    public function save(BankAccount $bankAccount): void;
}
