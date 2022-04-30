<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreatePaymentOrder;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class CreatePaymentOrderTest extends TestCase
{
    private Client $client;

    private string $host;

    public function setUp(): void
    {
        $this->host = 'paybybank_web';
        $this->client = new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyCreatedPaymentOrder(): void
    {
        # @ToDo retrieve bank's name from a trusted source.
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'amount' => 10,
           'creditorName' => 'Nikos Rigas',
           'bank' => 'ING'
        ]);

        $request = new Request('POST', "http://{$this->host}/payment/order", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $responseBody = json_decode($response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($responseBody->redirectUrl);
    }
}
