<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\GetPaymentOrderAuth;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class GetPaymentOrderAuthTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertSuccessfulAuthView(): void
    {
        $paymentOrderToken = $this->createPaymentOrder();

        $request = new Request('GET', "http://{$_ENV['WEB_API_HOST']}/payment/order/auth/{$paymentOrderToken}");
        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($responseBody);
    }

    private function createPaymentOrder(): string
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929310',
           'amount' => 10,
           'creditorName' => 'Nikos Rigas',
           'bank' => 'ING'
        ]);

        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/payment/order", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $responseBody = json_decode($response->getBody()->getContents());

        return $responseBody->token;
    }
}
