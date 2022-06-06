<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateAccessToken;

use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\IntegrationTestCase;

class CreateAccessTokenTest extends IntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyTokenCreation(): void
    {
        $body = json_encode(['mid' => $this->createMerchant()]);
        $response = $this->sendCreateJwtRequest($body);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertIsString($responsePayload->token);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testExpectErrorWithInvalidMid(): void
    {
        $body = json_encode(['mid' => $this->faker->randomKey()]);
        $response = $this->sendCreateJwtRequest($body);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertIsString($responsePayload->error);
    }
}
