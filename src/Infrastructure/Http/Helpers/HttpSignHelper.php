<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Helpers;

use OpenSSLAsymmetricKey;
use PayByBank\Domain\Http\Helper\HttpSignHelperInterface;

class HttpSignHelper implements HttpSignHelperInterface
{
    public function makeDigest(string $payload): string
    {
        return 'SHA-256=' . base64_encode(
                openssl_digest($payload, 'sha256', true)
            );
    }
    
    public function makeDate(): string
    {
        return gmdate('D, d M Y H:i:s T');
    }

    public function sign(OpenSSLAsymmetricKey $signKey, string $signString): string
    {
        openssl_sign($signString, $signature, $signKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }
}