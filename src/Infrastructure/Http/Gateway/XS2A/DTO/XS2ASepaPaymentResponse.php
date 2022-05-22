<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\XS2A\DTO;

class XS2ASepaPaymentResponse
{
    public readonly string $scaRedirectUrl;

    public readonly string $paymentId;

    public readonly string $transactionStatus;

    public function __construct(string $scaRedirectUrl, string $paymentId, string $transactionStatus)
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
        $this->paymentId = $paymentId;
        $this->transactionStatus = $transactionStatus;
    }
}
