<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway;

use GuzzleHttp\Psr7\Request;
use PayByBank\Domain\Http\DTO\AccessToken;
use PayByBank\Domain\Http\Exceptions\BadResponseException;
use PayByBank\Domain\Http\Helper\HttpSignHelperInterface;
use PayByBank\Infrastructure\Http\Gateway\Credential\IngCredentials;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class IngOAuth2Gateway
{
    private ClientInterface $client;

    private string $sandboxHost = 'https://api.sandbox.ing.com';

    private string $tokenPath = '/oauth2/token';

    private HttpSignHelperInterface $signHelper;

    private IngCredentials $credentials;

    public function __construct(ClientInterface $client, IngCredentials $credentials, HttpSignHelperInterface $signHelper)
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->signHelper = $signHelper;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(): AccessToken
    {
        $url = $this->sandboxHost . $this->tokenPath;
        $payload = 'grant_type=client_credentials';

        $date = $this->signHelper->makeDate();
        $digest = $this->signHelper->makeDigest($payload);
        $authHeader = $this->makeClientAuthHeader($this->credentials, $date, $digest);
        
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Date' => $date,
            'Digest' => $digest,
            'TPP-Signature-Certificate' => $this->credentials->getTppCert(),
            'Authorization' => $authHeader
        ];

        $request = new Request('POST', $url, $headers, $payload);
        $response = $this->client->sendRequest($request);
        $responsePayload = json_decode($response->getBody()->getContents());

        if (!$responsePayload || !isset($responsePayload->access_token)) {
            throw new BadResponseException('Response body error');
        }

        return new AccessToken(
            $responsePayload->access_token,
            $responsePayload->expires_in
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function authorizationUrl()
    {
        $accessToken = $this->createAccessToken();
    }

    private function makeClientAuthHeader(IngCredentials $credentials, string $date, string $digest): string
    {
        $signString = "(request-target): post /oauth2/token\ndate: {$date}\ndigest: {$digest}";
        $signature = $this->signHelper->sign($credentials->getSignKey(), $signString);

        return sprintf(
            'Signature keyId="%s",algorithm="rsa-sha256",headers="(request-target) date digest",signature="%s"',
            $credentials->getKeyId(),
            $signature
        );
    }
}