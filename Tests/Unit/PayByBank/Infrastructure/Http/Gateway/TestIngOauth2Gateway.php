<?php

declare(strict_types=1);

namespace Tests\Unit\PayByBank\Infrastructure\Http\Gateway;

use GuzzleHttp\Psr7\Response;
use PayByBank\Infrastructure\Http\Gateway\Credential\IngCredentials;
use PayByBank\Infrastructure\Http\Gateway\IngOAuth2Gateway;
use PayByBank\Infrastructure\Http\Helpers\HttpSignHelper;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class TestIngOauth2Gateway extends TestCase
{
    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $credential = $this->mockCredentials();
        $signHelper = new HttpSignHelper();
        $this->gateway = new IngOAuth2Gateway($this->client, $credential, $signHelper);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldParseAccessTokenFromJsonBody(): void
    {
        $this->mockSuccessfulAccessTokenCreationResponse();
        $accessToken = $this->gateway->createAccessToken();

        $this->assertEquals('test_access_token', $accessToken->getAccessToken());
        $this->assertEquals(905, $accessToken->getExpiresIn());
    }

    public function testShouldThrowExceptionWhenAccessTokenResponseBodyIsNotJson(): void
    {
        $this->mockInvalidJsonResponse();
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->createAccessToken();
    }

    public function testShouldTrowExceptionWhenAccessTokenIsMissing()
    {
        $this->mockJsonResponseWithoutAccessToken();
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->createAccessToken();
    }

    private function mockCredentials(): IngCredentials
    {
        $credentials = $this->createMock(IngCredentials::class);
        $credentials->method('getKeyId')->willReturn('key-id');
        $credentials->method('getTppCert')->willReturn('---begin-certificate---dwskndj3---end-certificate---');
        $credentials->method('getSignKey')->willReturn(openssl_pkey_new([
           'digest_alg' => 'sha256',
           'private_key_bits' => 1024,
           'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]));
        
        return $credentials;
    }

    private function mockJsonResponseWithoutAccessToken(): void
    {
        $body = '{"expires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';
        $headers = ['Content-Type' => 'application/json'];

        $this->mockHttpClientResponse($headers, $body);
    }

    private function mockInvalidJsonResponse(): void
    {
        $body = '{"access_token":"test_access_token",pires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';
        $headers = ['Content-Type' => 'application/json'];

        $this->mockHttpClientResponse($headers, $body);
    }

    private function mockSuccessfulAccessTokenCreationResponse(): void
    {
        $body = '{"access_token":"test_access_token","expires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';
        $headers = ['Content-Type' => 'application/json'];

        $this->mockHttpClientResponse($headers, $body);
    }

    private function mockHttpClientResponse(array $headers, string $body): void
    {
        $response = new Response(200, $headers, $body);
        $this->client->method('sendRequest')->willReturn($response);
    }
}
