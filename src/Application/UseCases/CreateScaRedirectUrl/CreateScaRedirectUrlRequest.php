<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateScaRedirectUrl;

class CreateScaRedirectUrlRequest
{
    public readonly string $paymentOrderToken;

    public readonly string $bankCode;

    public readonly string $psuIp;

    public function __construct(
        string $paymentOrderToken,
        string $bankCode,
        string $psuIp
    ) {
        $this->paymentOrderToken = $paymentOrderToken;
        $this->bankCode = $bankCode;
        $this->psuIp = $psuIp;
    }
}
