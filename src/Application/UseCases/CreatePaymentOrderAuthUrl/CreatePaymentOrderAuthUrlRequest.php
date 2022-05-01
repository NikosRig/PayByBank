<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl;

class CreatePaymentOrderAuthUrlRequest
{
    public readonly string $paymentOrderToken;

    public readonly ?string $psuIpAddress;

    public function __construct(string $paymentOrderToken, ?string $psuIpAddress)
    {
        $this->paymentOrderToken = $paymentOrderToken;
        $this->psuIpAddress = $psuIpAddress;
    }
}
