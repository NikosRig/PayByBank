<?php

declare(strict_types=1);

namespace Test\Integration;

use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class IntegrationTestCase extends TestCase
{
    protected readonly Client $client;

    public readonly Generator $faker;

    public function setUp(): void
    {
        $this->client = new Client();
        $this->faker = Factory::create();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createPaymentOrder(): string
    {
        $requestBody = json_encode(['amount' => 10]);
        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/payment/order", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->createAccessToken()}"
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $responseBody = json_decode($response->getBody()->getContents());

        return $responseBody->token;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createAccessToken(): string
    {
        $body = json_encode(['mid' => $this->createMerchant()]);
        $response = $this->sendCreateJwtRequest($body);
        $responsePayload = json_decode($response->getBody()->getContents());

        return $responsePayload->token;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sendCreateJwtRequest(string $body): ResponseInterface
    {
        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/oauth2/token", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $body);

        return $this->client->sendRequest($request);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createMerchant(): string
    {
        $body = json_encode([
             'firstName' => $this->faker->firstName(),
             'lastName' => $this->faker->lastName()
        ]);

        $createMerchantResponse = $this->sendCreateMerchantRequest($body);
        $createMerchantResponsePayload = json_decode(
            $createMerchantResponse->getBody()->getContents()
        );

        return $createMerchantResponsePayload->mid;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sendCreateMerchantRequest(string $body): ResponseInterface
    {
        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/merchant", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $body);

        return $this->client->sendRequest($request);
    }
}
