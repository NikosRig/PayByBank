<?php

namespace Test\Integration\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Client;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNACredentials;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class ABNAGatewayTest extends TestCase
{
    private ClientInterface $client;

    private ABNACredentials $credentials;

    public function setUp(): void
    {
        $clientOptions = [
            'cert' => '/var/www/html/var/certs/ABNA/sandbox/tpp.crt',
            'ssl_key' => '/var/www/html/var/certs/ABNA/sandbox/tpp.key'
        ];
        $this->client = new Client($clientOptions);
        $this->credentials = new ABNACredentials(
            'TPP_test',
            'Pfkjb9TG3erj7uFlByFgZWixz1uKPlfk',
            'https://localhost/auth',
            true
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertWillReturnAccessToken(): void
    {
        $gateway = new ABNAGateway($this->client, $this->credentials);
        $accessToken = $gateway->createAccessToken('psd2:payment:sepa:write');

        $this->assertIsString($accessToken);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSepaPaymentRegistrationResponseWillHasTheRequiredParams(): void
    {
        $gateway = new ABNAGateway($this->client, $this->credentials);
        $request = new RegisterSepaPaymentRequest('NL12ABNA9999876523', 'Nikos Rigas', 100);
        $response = $gateway->registerSepaPayment($request);

        $this->assertIsString($response->scaRedirectUrl);
        $this->assertIsString($response->transactionId);
        $this->assertIsString($response->accessToken);
    }
}
