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

    private ABNACredentials $credentials;

    public function __construct(ClientInterface $client, ABNACredentials $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(string $scope): string
    {
        $sandboxUrl = 'https://auth-mtls-sandbox.abnamro.com/as/token.oauth2';
        $productionUrl = 'https://auth.connect.abnamro.com:8443/as/token.oauth2';
        $url = $this->credentials->isSandbox ? $sandboxUrl : $productionUrl;
        $body = "grant_type=client_credentials&client_id={$this->credentials->clientId}&scope={$scope}";

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

    /**
     * @throws ClientExceptionInterface
     */
    public function sepaPayment(ABNASepaPaymentRequest $sepaRequest): ABNASepaPaymentResponse
    {
        $accessToken = $this->createAccessToken('psd2:payment:sepa:write');
        $sandboxUrl = 'https://api-sandbox.abnamro.com/v1/payments';
        $productionUrl = 'https://api.abnamro.com/v1/payments';
        $url = $this->credentials->isSandbox ? $sandboxUrl : $productionUrl;

        $body = json_encode([
            'counterpartyAccountNumber' => $sepaRequest->creditorIban,
            'counterpartyName' => $sepaRequest->creditorName,
            'amount' => $sepaRequest->amount,
        ]);

        $request = new Request('POST', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
            'API-Key' => $this->credentials->apiKey
        ], $body);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        if (!isset($responsePayload->transactionId)
            || !isset($responsePayload->status)
            || $responsePayload->status != 'STORED'
        ) {
            throw new BadResponseException($responseBody, $response->getStatusCode());
        }

        return new ABNASepaPaymentResponse($responsePayload->transactionId, $accessToken);
    }
}
