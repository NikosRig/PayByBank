<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreatePaymentOrder;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\IntegrationTestCase;

class CreatePaymentOrderTest extends IntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyCreatedPaymentOrder(): void
    {
        $accessToken = $this->createAccessToken();
        $requestBody = json_encode(['amount' => 10, 'description' => 'test description']);

        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/payment/orders/create", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $responseBody = json_decode($response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($responseBody->token);
    }
}
