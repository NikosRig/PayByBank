<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\Helper;

use OpenSSLAsymmetricKey;

interface HttpSignHelperInterface
{
    public function makeDigest(string $payload): string;

    public function makeDate(): string;

    public function sign(OpenSSLAsymmetricKey $signKey, string $signString): string;
}