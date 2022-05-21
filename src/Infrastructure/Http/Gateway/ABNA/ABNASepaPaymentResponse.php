<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

class ABNASepaPaymentResponse
{
    public readonly string $transactionId;

    public readonly string $accessToken;

    public function __construct(string $transactionId, string $accessToken)
    {
        $this->transactionId = $transactionId;
        $this->accessToken = $accessToken;
    }
}
