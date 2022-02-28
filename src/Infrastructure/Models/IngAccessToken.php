<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Models;

use PayByBank\Domain\Models\AccessTokenInterface;

class IngAccessToken implements AccessTokenInterface
{
    private string $token;

    private int $expiresIn;

    private string $clientId;

    private string $scope;

    public function __construct(string $token, int $expiresIn, string $clientId, string $scope)
    {
        $this->token = $token;
        $this->expiresIn = $expiresIn;
        $this->clientId = $clientId;
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return int
     */
    public function getExpires(): int
    {
        return $this->expiresIn;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return json_encode([
            'token' => $this->getToken(),
            'scope' => $this->getScope(),
            'expires_in' => $this->getExpires(),
            'client_id' => $this->getClientId()
        ]);
    }
}