<?php

declare(strict_types=1);

namespace Test\Integration\WebApi\Actions\CreateBankAccount;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Test\Integration\IntegrationTestCase;

class CreateBankAccountTest extends IntegrationTestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldCreateBankAccount(): void
    {
        $mid = $this->createMerchant();
        $body = json_encode([
            'mid' => $mid,
            'iban' => $this->faker->iban(),
            'accountHolderName' => $this->faker->name()
        ]);

        $request = new Request(
            'PUT',
            "http://{$_ENV['WEB_API_HOST']}/merchants/accounts",
            [],
            $body
        );
        $response = $this->client->sendRequest($request);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
