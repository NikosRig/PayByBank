<?php

declare(strict_types=1);

namespace Config;

class AccessTokenConfig
{
    public readonly string $issuer;

    public readonly string $secretKey;

    public readonly int $tokenLifeTimeSeconds;

    public function __construct(string $issuer, string $secretKey, int $tokenLifeTimeSeconds)
    {
        $this->issuer = $issuer;
        $this->secretKey = $secretKey;
        $this->tokenLifeTimeSeconds = $tokenLifeTimeSeconds;
    }
}
