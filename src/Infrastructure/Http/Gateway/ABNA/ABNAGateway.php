<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Psr7\Request;
use PayByBank\Infrastructure\Http\Exceptions\BadResponseException;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class ABNAGateway
{
    private const SEPA_PAYMENT_SCOPE = 'psd2:payment:sepa:write';

    private ClientInterface $client;

    private ABNACredentials $credentials;

    private string $accessTokenUrl;

    private string $oAuthUrl;

    private string $paymentsUrl;

    public function __construct(ClientInterface $client, ABNACredentials $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->setupGatewayUrls($this->credentials->isSandbox);
    }

    private function setupGatewayUrls(bool $isSandbox): void
    {
        $accessTokenSandboxUrl = 'https://auth-mtls-sandbox.abnamro.com/as/token.oauth2';
        $accessTokenProductionUrl = 'https://auth.connect.abnamro.com:8443/as/token.oauth2';
        $this->accessTokenUrl = $isSandbox ? $accessTokenSandboxUrl : $accessTokenProductionUrl;

        $sandboxOAuthUrl = 'https://auth-sandbox.abnamro.com/as/authorization.oauth2';
        $productionOAuthUrl = 'https://auth.connect.abnamro.com:8443/as/token.oauth2';
        $this->oAuthUrl = $isSandbox ? $sandboxOAuthUrl : $productionOAuthUrl;

        $paymentsSandboxUrl = 'https://api-sandbox.abnamro.com/v1/payments';
        $paymentsProductionUrl = 'https://api.abnamro.com/v1/payments';
        $this->paymentsUrl = $isSandbox ? $paymentsSandboxUrl : $paymentsProductionUrl;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(string $scope): string
    {
        $body = "grant_type=client_credentials&client_id={$this->credentials->clientId}&scope={$scope}";

        $request = new Request('POST', $this->accessTokenUrl, [
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
    public function registerSepaPayment(RegisterSepaPaymentRequest $registerSepaPaymentRequest): RegisterSepaPaymentResponse
    {
        $accessToken = $this->createAccessToken(self::SEPA_PAYMENT_SCOPE);

        $body = json_encode([
            'counterpartyAccountNumber' => $registerSepaPaymentRequest->creditorIban,
            'counterpartyName' => $registerSepaPaymentRequest->creditorName,
            'amount' => $registerSepaPaymentRequest->amount,
        ]);

        $request = new Request('POST', $this->paymentsUrl, [
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

        $scaRedirectUrl = $this->createScaRedirectUrl(
            $responsePayload->transactionId,
            self::SEPA_PAYMENT_SCOPE
        );

        return new RegisterSepaPaymentResponse(
            $responsePayload->transactionId,
            $accessToken,
            $scaRedirectUrl
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function authorizeCode(string $code, string $accessToken)
    {
        $body = "grant_type=authorization_code&client_id={$this->credentials->clientId}&code={$code}&redirect_uri={$this->credentials->tppRedirectUrl}";

        $request = new Request('POST', $this->oAuthUrl, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer '. $accessToken
        ], $body);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        return json_decode($responseBody, true);
    }

    private function createScaRedirectUrl(string $transactionId, string $scope): string
    {
        $query = http_build_query([
            'scope' => $scope,
            'client_id' => $this->credentials->clientId,
            'transactionId' => $transactionId,
            'response_type' => 'code',
            'flow' => 'code',
            'redirect_uri' => $this->credentials->tppRedirectUrl
        ]);

        return "{$this->oAuthUrl}?$query";
    }
}
