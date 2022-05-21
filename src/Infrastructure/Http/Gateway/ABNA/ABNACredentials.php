<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

class ABNACredentials
{
    public readonly string $clientId;

    public readonly string $apiKey;

    public readonly bool $isSandbox;

    public function __construct(string $clientId, string $apiKey, bool $isSandbox = true)
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->isSandbox = $isSandbox;
    }
}