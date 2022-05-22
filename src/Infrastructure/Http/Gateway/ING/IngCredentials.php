<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ING;

use OpenSSLAsymmetricKey;

class IngCredentials
{
    public readonly OpenSSLAsymmetricKey $signKey;

    public readonly string $keyId;

    public readonly string $tppCert;

    public readonly string $tppRedirectUrl;

    public readonly bool $isSandbox;

    public function __construct(
        OpenSSLAsymmetricKey $signKey,
        string $tppCert,
        string $keyId,
        string $tppRedirectUrl,
        bool $isSandbox = true
    ) {
        $this->signKey = $signKey;
        $this->tppCert = $tppCert;
        $this->keyId = $keyId;
        $this->tppRedirectUrl = $tppRedirectUrl;
        $this->isSandbox = $isSandbox;
    }
}
