<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http\Gateway;

use GuzzleHttp\Psr7\Response;
use PayByBank\Infrastructure\Http\Gateway\Credential\IngCredentials;
use PayByBank\Infrastructure\Http\Gateway\IngAuthGateway;
use PayByBank\Infrastructure\Http\Helpers\HttpSignHelper;
use PayByBank\Infrastructure\Models\IngAccessToken;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestIngAuthGateway extends TestCase
{
    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $credential = $this->mockCredentials();
        $signHelper = new HttpSignHelper();
        $this->gateway = new IngAuthGateway($this->client, $credential, $signHelper);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldParseAccessTokenFromJsonBody(): void
    {
        $this->client->method('sendRequest')->willReturn(
            $this->successfulAccessTokenCreationResponse()
        );
        $accessToken = $this->gateway->createAccessToken();

        $this->assertEquals('test_access_token', $accessToken->getToken());
        $this->assertEquals(905, $accessToken->getExpires());
    }

    public function testShouldThrowExceptionWhenAccessTokenResponseBodyIsNotJson(): void
    {
        $this->client->method('sendRequest')->willReturn(
            $this->invalidAccessTokenJsonBodyResponse()
        );
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->createAccessToken();
    }

    public function testShouldThrowExceptionWhenAccessTokenIsMissing(): void
    {
        $this->client->method('sendRequest')->willReturn(
            $this->jsonResponseWithoutAccessToken()
        );
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->createAccessToken();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertShouldParseAuthUrlFromJson(): void
    {
        $tokenPath = $this->gateway->tokenPath;
        $accessTokenBody = $this->successfulAccessTokenCreationJsonResponse();
        $authorizationUrlBody = $this->successfulAuthorizationUrlCreationJsonResponse();

        $this->client->method('sendRequest')->willReturnCallback(function (RequestInterface $request) use ($tokenPath, $accessTokenBody, $authorizationUrlBody)  {
            if ($request->getRequestTarget() == $tokenPath) {
                return new Response(200, [], $accessTokenBody);
            }
            return new Response(200, [], $authorizationUrlBody);
        });

        $this->assertIsString($this->gateway->getAuthorizationUrl());
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

    private function jsonResponseWithoutAccessToken(): ResponseInterface
    {
        $body = '{"expires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';
        $headers = ['Content-Type' => 'application/json'];

        return new Response(200, $headers, $body);
    }

    private function invalidAccessTokenJsonBodyResponse(): ResponseInterface
    {
        $body = '{"access_token":"test_access_token","expires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';

        return new Response(200, [], $body);
    }

    public function successfulAccessTokenCreationResponse(): ResponseInterface
    {
        $body = $this->successfulAccessTokenCreationJsonResponse();

        return new Response(200, [], $body);
    }

    private function successfulAuthorizationUrlCreationJsonResponse(): string
    {
        return '{"location":"https://myaccount.sandbox.ing.com/granting/dd157ab6-d353-451d-a8e4-376ae9/NL"}';
    }

    private function successfulAccessTokenCreationJsonResponse(): string
    {
        return '{"access_token":"test_access_token","expires_in":905,"scope":"payment-accounts:orders:create granting","token_type":"Bearer","keys":[{"kty":"RSA","n":"3l3rdz4hy","e":"AQAB","use":"sig","alg":"RS256","x5t":"26e5d932936"}],"client_id":"5ca1ab1e-c0ca"}';
    }
}
