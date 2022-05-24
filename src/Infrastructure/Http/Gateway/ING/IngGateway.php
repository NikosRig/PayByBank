<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ING;

use GuzzleHttp\Psr7\Request;
use PayByBank\Infrastructure\Http\Gateway\Exceptions\BadResponseException;
use PayByBank\Infrastructure\Http\Gateway\ING\DTO\CreateAccessTokenResponse;
use PayByBank\Infrastructure\Http\HttpSigner;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class IngGateway
{
    public const PAYMENT_INITIATION_SCOPE = 'payment-accounts:orders:create';

    private ClientInterface $client;

    private IngCredentials $credentials;

    private HttpSigner $httpSigner;

    private readonly string $accessTokenPath;

    private readonly string $accessTokenUrl;

    private readonly string $host;

    public function __construct(ClientInterface $client, IngCredentials $credentials, HttpSigner $httpSigner)
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->httpSigner = $httpSigner;
        $this->setupGatewayUrls($credentials->isSandbox);
    }

    private function setupGatewayUrls(bool $isSandbox): void
    {
        $this->host = $isSandbox ? 'https://api.sandbox.ing.com' : 'https://api.ing.com';

        $this->accessTokenPath = '/oauth2/token';
        $this->accessTokenUrl = $this->host . $this->accessTokenPath;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(string $scope): CreateAccessTokenResponse
    {
        $date = $this->httpSigner->makeDate();
        $requestBody = 'grant_type=client_credentials&scope=' . urlencode($scope);
        $digest = $this->httpSigner->makeDigest($requestBody);

        $signString = "(request-target): post {$this->accessTokenPath}\ndate: {$date}\ndigest: {$digest}";
        $signature = $this->httpSigner->sign($this->credentials->signKey, $signString);
        $signatureHeader = $this->makeSignatureHeader($this->credentials->keyId, $signature);

        $request = new Request(
            'POST',
            $this->accessTokenUrl,
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Date' => $date,
                'Digest' => $digest,
                'TPP-Signature-Certificate' => $this->credentials->tppCert,
                'Authorization' => 'Signature ' . $signatureHeader
            ],
            $requestBody
        );

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        if (!isset($responsePayload->access_token) || !isset($responsePayload->client_id)) {
            throw new BadResponseException($responseBody, $response->getStatusCode());
        }

        return new CreateAccessTokenResponse(
            $responsePayload->access_token,
            $responsePayload->expires_in,
            $responsePayload->client_id,
            $responsePayload->scope
        );
    }

    private function makeSignatureHeader(string $keyId, string $signature): string
    {
        return sprintf(
            'keyId="%s",algorithm="rsa-sha256",headers="(request-target) date digest",signature="%s"',
            $keyId,
            $signature
        );
    }
}
