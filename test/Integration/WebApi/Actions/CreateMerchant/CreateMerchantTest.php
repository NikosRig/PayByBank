<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateMerchant;

use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\IntegrationTestCase;

class CreateMerchantTest extends IntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testSuccessfullyCreatedMerchant(): void
    {
        $body = json_encode([
            'firstName' => 'merchant',
            'lastName' => 'merchant'
         ]);

        $response = $this->sendCreateMerchantRequest($body);
        $responsePayload =  json_decode($response->getBody()->getContents());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertIsString($responsePayload->mid);
    }
}
