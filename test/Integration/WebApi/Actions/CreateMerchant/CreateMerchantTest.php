<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateMerchant;

use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\WebApi\Actions\ActionIntegrationTestCase;

class CreateMerchantTest extends ActionIntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyCreatedMerchant(): void
    {
        $payload = json_encode([
            'firstName' => 'merchant',
            'lastName' => 'merchant'
         ]);

        $response = $this->createMerchant($payload);
        $responsePayload =  json_decode($response->getBody()->getContents());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertIsString($responsePayload->mid);
    }
}
