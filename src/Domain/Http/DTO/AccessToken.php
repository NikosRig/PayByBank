<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\DTO;

class AccessToken
{
    private string $accessToken;

    private int $expiresIn;

    private ?string $scope;

    public function __construct(string $accessToken, int $expiresIn, ?string $scope = null)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->scope = $scope;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}