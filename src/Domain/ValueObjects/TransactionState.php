<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

use DateTime;

class TransactionState
{
    public function __construct(
        public readonly string $id,
        public readonly DateTime $dateCreated,
        public readonly string $bankAccountId,
        public readonly string $paymentOrderToken,
        public readonly string $scaRedirectUrl,
        public readonly string $transactionId,
        public readonly string $psuIp,
        public readonly array $bankData
    ) {
    }
}
