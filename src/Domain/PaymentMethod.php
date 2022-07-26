<?php

declare(strict_types=1);

namespace PayByBank\Domain;

use Exception;

interface PaymentMethod
{
    public function getBankCode(): string;

    /**
     * @throws Exception
     */
    public function createScaRedirectUrl(ScaTransactionData $scaTransactionData): void;

    /**
     * @throws Exception
     */
    public function executePayment(TransactionData $transactionData): void;
}
