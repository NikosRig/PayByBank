<?php

namespace Test\Integration\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Client;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAScope;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class ABNAGatewayTest extends TestCase
{
    private ClientInterface $client;

    public function setUp(): void
    {
        $clientOptions = [
            'cert' => '/var/www/html/var/certs/AbnAmro/sandbox/tpp.crt',
            'ssl_key' => '/var/www/html/var/certs/AbnAmro/sandbox/tpp.key'
        ];
        $this->client = new Client($clientOptions);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertWillReturnAccessToken(): void
    {
        $gateway = new ABNAGateway($this->client, true);
        $accessToken = $gateway->createAccessToken('TPP_test', ABNAScope::SEPA_PAYMENT);

        $this->assertIsString($accessToken);
    }
}
