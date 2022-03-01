<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway;

use GuzzleHttp\Psr7\Request;
use PayByBank\Domain\Http\Exceptions\BadResponseException;
use PayByBank\Domain\Http\Helper\HttpSignHelperInterface;
use PayByBank\Infrastructure\Http\Gateway\Credential\IngCredentials;
use PayByBank\Infrastructure\Models\IngAccessToken;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class IngAuthGateway
{
    private ClientInterface $client;

    private string $sandboxHost = 'https://api.sandbox.ing.com';

    public readonly string $tokenPath;

    private HttpSignHelperInterface $signHelper;

    private IngCredentials $credentials;

    private string $paymentInitiationScope = 'payment-accounts:orders:create';

    public function __construct(ClientInterface $client, IngCredentials $credentials, HttpSignHelperInterface $signHelper)
    {
        $this->tokenPath = '/oauth2/token';
        $this->client = $client;
        $this->credentials = $credentials;
        $this->signHelper = $signHelper;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(): IngAccessToken
    {
        $url = $this->sandboxHost . $this->tokenPath;
        $date = $this->signHelper->makeDate();
        $requestBody = 'grant_type=client_credentials&scope=' . urlencode($this->paymentInitiationScope);
        $digest = $this->signHelper->makeDigest($requestBody);

        $signString = "(request-target): post {$this->tokenPath}\ndate: {$date}\ndigest: {$digest}";
        $signature = $this->signHelper->sign($this->credentials->getSignKey(), $signString);
        
        $request = new Request('POST', $url, [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Date' => $date,
            'Digest' => $digest,
            'TPP-Signature-Certificate' => $this->credentials->getTppCert(),
            'Authorization' => 'Signature ' . $this->makeSignatureHeader($this->credentials->getKeyId(), $signature)],
            $requestBody
        );

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();

        $jsonResponse = json_decode($responseBody);

        if (!$jsonResponse || empty($jsonResponse->access_token)) {
            throw new BadResponseException('BadResponse: ' . $responseBody);
        }

        return new IngAccessToken(
            $jsonResponse->access_token,
            $jsonResponse->expires_in,
            $jsonResponse->client_id,
            $jsonResponse->scope
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function getAuthorizationUrl(): string
    {
        $accessToken = $this->createAccessToken();
        $reqPath = sprintf("/oauth2/authorization-server-url?scope=%s&redirect_uri=%s&country_code=%s",
            urlencode($accessToken->getScope()),
            $this->credentials->getRedirectUrl(),
            'NL'
        );
        $authUrl = $this->sandboxHost . $reqPath;
        $date = $this->signHelper->makeDate();
        $digest = $this->signHelper->makeDigest('');
        $signature = $this->signHelper->sign(
            $this->credentials->getSignKey(),
            "(request-target): get {$reqPath}\ndate: {$date}\ndigest: {$digest}"
        );

        $request = new Request('GET', $authUrl, [
            'Date' => $date,
            'Digest' => $digest,
            'TPP-Signature-Certificate' => $this->credentials->getTppCert(),
            'Authorization' => 'Bearer '.$accessToken->getToken(),
            'Signature' => $this->makeSignatureHeader($accessToken->getClientId(), $signature)
        ]);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $jsonPayload = json_decode($responseBody);

        if (!$jsonPayload || empty($jsonPayload->location)) {
            throw new BadResponseException('BadResponse: ' . $responseBody);
        }

        $queryParams = [
            'client_id' => $accessToken->getClientId(),
            'redirect_uri' => $this->credentials->getRedirectUrl(),
            'scope' => $this->paymentInitiationScope
        ];

        return $jsonPayload->location . '?' . http_build_query($queryParams);
    }

    private function makeSignatureHeader(string $keyId, $signature): string
    {
        return sprintf(
            'keyId="%s",algorithm="rsa-sha256",headers="(request-target) date digest",signature="%s"',
            $keyId,
            $signature
        );
    }
}