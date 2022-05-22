<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA\DTO;

class AuthorizeCodeResponse
{
    public readonly string $accessToken;

    public readonly string $refreshToken;

    public readonly int $expiresIn;

    public function __construct(string $accessToken, string $refreshToken, int $expiresIn)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn = $expiresIn;
    }
}
