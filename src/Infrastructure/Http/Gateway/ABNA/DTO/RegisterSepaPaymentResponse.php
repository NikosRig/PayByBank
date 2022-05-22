<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA\DTO;

class RegisterSepaPaymentResponse
{
    public readonly string $transactionId;

    public readonly string $accessToken;

    public readonly string $scaRedirectUrl;

    public function __construct(string $transactionId, string $accessToken, string $scaRedirectUrl)
    {
        $this->transactionId = $transactionId;
        $this->accessToken = $accessToken;
        $this->scaRedirectUrl = $scaRedirectUrl;
    }
}
