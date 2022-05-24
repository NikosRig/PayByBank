<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ING\DTO;

class CreateAccessTokenResponse
{
    public readonly string $accessToken;

    public readonly int $expiresIn;

    public readonly string $scope;

    public readonly string $clientId;

    public function __construct(string $accessToken, int $expiresIn, string $clientId, string $scope)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->clientId = $clientId;
        $this->scope = $scope;
    }
}
