<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\Account;

interface AccountRepository
{
    public function findByBankCodeAndMerchantId(string $bankCode, string $merchantId): ?Account;

    public function save(Account $creditorAccount): void;
}
