<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Psr7\Request;
use PayByBank\Infrastructure\Http\Exceptions\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class ABNAGateway
{
    private ClientInterface $client;

    private bool $isSandbox;

    public function __construct(ClientInterface $client, bool $isSandbox = true)
    {
        $this->client = $client;
        $this->isSandbox = $isSandbox;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(string $clientId, ABNAScope $scope): string
    {
        $url = 'https://auth-mtls-sandbox.abnamro.com/as/token.oauth2';
        $body = "grant_type=client_credentials&client_id={$clientId}&scope={$scope->value}";

        $request = new Request('POST', $url, [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $body);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents() ?? '';
        $responsePayload = json_decode($responseBody);

        if (!isset($responsePayload->access_token) || !is_string($responsePayload->access_token)) {
            throw new BadResponseException($responseBody, $response->getStatusCode());
        }

        return $responsePayload->access_token;
    }
}
