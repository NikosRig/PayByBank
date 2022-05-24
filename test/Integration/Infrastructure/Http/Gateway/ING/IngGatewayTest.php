<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Http\Gateway\ING;

use GuzzleHttp\Client;
use PayByBank\Infrastructure\Http\Gateway\ING\IngCredentials;
use PayByBank\Infrastructure\Http\Gateway\ING\IngGateway;
use PayByBank\Infrastructure\Http\HttpSigner;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class IngGatewayTest extends TestCase
{
    private static IngCredentials $credentials;

    private IngGateway $gateway;

    public static function setUpBeforeClass(): void
    {
        $signKey = openssl_pkey_get_private('file:///var/www/html/var/certs/ING/sandbox/client_signing.key');
        $tppCertContents = file_get_contents('/var/www/html/var/certs/ING/sandbox/tpp.cer');
        $tppCert = str_replace(PHP_EOL, '', $tppCertContents);
        self::$credentials = new IngCredentials(
            $signKey,
            $tppCert,
            'SN=5E4299BE',
            'https://localhost/auth'
        );
    }

    public function setUp(): void
    {
        $clientOptions = [
            'cert' => '/var/www/html/var/certs/ING/sandbox/tls.cer',
            'ssl_key' => '/var/www/html/var/certs/ING/sandbox/tls.key'
        ];
        $client = new Client($clientOptions);
        $this->gateway = new IngGateway($client, self::$credentials, new HttpSigner());
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertAccessTokenCreation(): void
    {
        $accessToken = $this->gateway->createAccessToken(IngGateway::PAYMENT_INITIATION_SCOPE);

        $this->assertIsString($accessToken->accessToken);
        $this->assertIsString($accessToken->scope);
        $this->assertIsString($accessToken->clientId);
    }
}
