<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class ActionIntegrationTestCase extends TestCase
{
    private readonly Client $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function createMerchant(string $payload): ResponseInterface
    {
        $request = new Request('POST', "http://{$_ENV['WEB_API_HOST']}/merchant", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $payload);

        return $this->client->sendRequest($request);
    }
}
