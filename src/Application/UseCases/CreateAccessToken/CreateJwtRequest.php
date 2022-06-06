<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateAccessToken;

class CreateJwtRequest
{
    public readonly string $mid;

    public readonly string $jwtIssuer;

    public readonly string $jwtSecretKey;

    public readonly int $tokenLifeTimeSeconds;

    public function __construct(
        string $mid,
        string $jwtIssuer,
        string $jwtSecretKey,
        int $tokenLifeTimeSeconds
    ) {
        $this->mid = $mid;
        $this->jwtIssuer = $jwtIssuer;
        $this->jwtSecretKey = $jwtSecretKey;
        $this->tokenLifeTimeSeconds = $tokenLifeTimeSeconds;
    }
}
