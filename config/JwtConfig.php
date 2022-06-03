<?php

declare(strict_types=1);

namespace Config;

class JwtConfig
{
    public readonly string $jwtIssuer;

    public readonly string $jwtSecretKey;

    public readonly int $tokenLifeTimeSeconds;

    public function __construct(string $jwtIssuer, string $jwtSecretKey, int $tokenLifeTimeSeconds)
    {
        $this->jwtIssuer = $jwtIssuer;
        $this->jwtSecretKey = $jwtSecretKey;
        $this->tokenLifeTimeSeconds = $tokenLifeTimeSeconds;
    }
}
