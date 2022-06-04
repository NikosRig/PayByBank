<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateJwt;

use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\IntegrationTestCase;

class CreateJwtTest extends IntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyJwtCreation(): void
    {
        $body = json_encode(['mid' => $this->createMid()]);
        $response = $this->sendCreateJwtRequest($body);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertIsString($responsePayload->token);
    }
}
