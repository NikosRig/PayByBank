<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateMerchant;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class CreateMerchantTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyCreatedMerchant(): void
    {
        $requestBody = json_encode([
            'username' => 'mer_' .bin2hex(openssl_random_pseudo_bytes(24)),
            'password' => 'password'
         ]);

        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/merchant", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(null, $response->getBody()->getContents());
    }
}
