<?php

namespace Test\Unit\Infrastructure\Http\Gateway\ING;

use Http\Mock\Client;
use PayByBank\Infrastructure\Http\Gateway\Exceptions\BadResponseException;
use PayByBank\Infrastructure\Http\Gateway\ING\IngCredentials;
use PayByBank\Infrastructure\Http\Gateway\ING\IngGateway;
use PayByBank\Infrastructure\Http\HttpSigner;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Infrastructure\Http\Gateway\GatewayTestCase;

class IngGatewayTest extends GatewayTestCase
{
    private readonly Client $client;

    private readonly IngGateway $gateway;

    public function setUp(): void
    {
        $this->client = new Client();
        $credentials = new IngCredentials(
            openssl_pkey_new(),
            'tppCert',
            '',
            'https://localhost/auth'
        );
        $this->gateway = new IngGateway($this->client, $credentials, new HttpSigner());
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAccessTokenCreation(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $accessToken = $this->gateway->createAccessToken(IngGateway::PAYMENT_INITIATION_SCOPE);

        $this->assertEquals('access-token', $accessToken->accessToken);
        $this->assertEquals(905, $accessToken->expiresIn);
        $this->assertEquals('client-id', $accessToken->clientId);
        $this->assertEquals('scope', $accessToken->scope);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertExceptionWhenAccessTokenMissingFromResponse(): void
    {
        $this->client->addResponse($this->mockResponse(''));
        $this->expectException(BadResponseException::class);
        $this->gateway->createAccessToken(IngGateway::PAYMENT_INITIATION_SCOPE);
    }

    private function getAccessTokenResponse(): ResponseInterface
    {
        $responseBody = '{"access_token":"access-token","expires_in":905,"scope":"scope","token_type":"Bearer","keys":[{"kty":"RSA","n":"dwuidhwuh3fde4","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d93293"}],"client_id":"client-id"}';
        return $this->mockResponse($responseBody);
    }
}
