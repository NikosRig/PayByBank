<?php

namespace Test\Unit\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNACredentials;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class ABNAGatewayTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $credentials = new ABNACredentials(
            'clientId',
            'apiKey',
            'https://localhost/auth',
            true
        );
        $this->client = new Client();
        $this->gateway = new ABNAGateway($this->client, $credentials);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldReturnAccessToken(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $accessToken = $this->gateway->createAccessToken('scope');

        $this->assertEquals('0003mBb4xxDCqNxnyS4JmAp8dazy', $accessToken);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testExpectExceptionWhenAccessTokenMissing(): void
    {
        $response = new Response(200, [], json_encode([]));
        $this->client->addResponse($response);

        $this->expectException(ClientExceptionInterface::class);
        $this->gateway->createAccessToken('scope');
    }

    public function testSepaPaymentRegistrationWhenResponseHasNotTransactionId(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $sepaPaymentResponse = new Response(200, [], json_encode(['status' => 'STORED']));
        $this->client->addResponse($sepaPaymentResponse);
        $request = new RegisterSepaPaymentRequest('iban', 'Nikos Rigas', 10);

        $this->expectException(ClientExceptionInterface::class);
        $this->gateway->registerSepaPayment($request);
    }

    public function testSepaPaymentRegistrationWhenResponseHasInvalidStatus(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $sepaPaymentResponse = new Response(200, [], json_encode([
            'transactionId' => 'dwxcefgve',
            'status' => 'REJECTED'
        ]));
        $this->client->addResponse($sepaPaymentResponse);
        $request = new RegisterSepaPaymentRequest('iban', 'Nikos Rigas', 10);

        $this->expectException(ClientExceptionInterface::class);
        $this->gateway->registerSepaPayment($request);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSepaPaymentRegistrationResponseWillHasTransactionId(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $this->client->addResponse($this->getSepaPaymentStoredResponse());
        $request = new RegisterSepaPaymentRequest('iban', 'Nikos Rigas', 10);
        $response = $this->gateway->registerSepaPayment($request);

        $this->assertEquals('VS8BVLWKFJ1653162174254', $response->transactionId);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSepaPaymentRegistrationResponseWillHasScaRedirectUrl(): void
    {
        $this->client->addResponse($this->getAccessTokenResponse());
        $this->client->addResponse($this->getSepaPaymentStoredResponse());
        $request = new RegisterSepaPaymentRequest('iban', 'Nikos Rigas', 10);
        $response = $this->gateway->registerSepaPayment($request);

        $this->assertIsString($response->scaRedirectUrl);
    }

    private function getSepaPaymentStoredResponse(): ResponseInterface
    {
        $responseBody = '{"transactionId":"VS8BVLWKFJ1653162174254","status":"STORED"}';

        return new Response(200, [], $responseBody);
    }

    private function getAccessTokenResponse(): ResponseInterface
    {
        $responseBody = '{"access_token":"0003mBb4xxDCqNxnyS4JmAp8dazy","token_type":"Bearer","expires_in":7200}';

        return new Response(200, [], $responseBody);
    }
}
