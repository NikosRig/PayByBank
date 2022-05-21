<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

class ABNACredentials
{
    public readonly string $clientId;

    public readonly string $apiKey;

    public readonly string $tppRedirectUrl;

    public readonly bool $isSandbox;

    public function __construct(string $clientId, string $apiKey, string $tppRedirectUrl, bool $isSandbox = true)
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->tppRedirectUrl = $tppRedirectUrl;
        $this->isSandbox = $isSandbox;
    }
}
