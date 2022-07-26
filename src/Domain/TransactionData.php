<?php

declare(strict_types=1);

namespace PayByBank\Domain;

class TransactionData
{
    public function __construct(
        public readonly string $transactionId,
        public readonly ?string $authCode = null,
        public readonly array $bankData = []
    ) {
    }
}
