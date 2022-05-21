<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

class ABNASepaPaymentResponse
{
    public readonly string $transactionId;

    public readonly string $accessToken;

    public readonly string $authUrl;

    public function __construct(string $transactionId, string $accessToken, string $authUrl)
    {
        $this->transactionId = $transactionId;
        $this->accessToken = $accessToken;
        $this->authUrl = $authUrl;
    }
}
