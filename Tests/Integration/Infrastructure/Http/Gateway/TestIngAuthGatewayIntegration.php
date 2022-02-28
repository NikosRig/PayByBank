<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Http\Gateway;

use GuzzleHttp\Client;
use PayByBank\Infrastructure\Http\Gateway\Credential\IngCredentials;
use PayByBank\Infrastructure\Http\Gateway\IngAuthGateway;
use PayByBank\Infrastructure\Http\Helpers\HttpSignHelper;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class TestIngAuthGatewayIntegration extends TestCase
{
    public function setUp(): void
    {
        $clientOptions = [
            'cert' => '/var/www/html/var/certs/ING/sandbox/tls.cer',
            'ssl_key' => '/var/www/html/var/certs/ING/sandbox/tls.key'
        ];
        $this->client = new Client($clientOptions);

        $signKeyPath = '/var/www/html/var/certs/ING/sandbox/client_signing.key';
        $tppCert = file_get_contents('/var/www/html/var/certs/ING/sandbox/tpp.cer');
        $keyId = 'SN=5E4299BE';

        $credential = new IngCredentials($signKeyPath, $tppCert, $keyId);

        $this->gateway = new IngAuthGateway($this->client, $credential, new HttpSignHelper());
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldParseAccessToken(): void
    {
        $accessToken = $this->gateway->createAccessToken();

        $this->assertIsString($accessToken->getToken());
        $this->assertIsInt($accessToken->getExpires());
        $this->assertIsString($accessToken->getClientId());
        $this->assertIsString($accessToken->getScope());
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldParseAuthUrl(): void
    {
        $authUrl = $this->gateway->getAuthorizationUrl();

        $this->assertIsString($authUrl);
    }
}
