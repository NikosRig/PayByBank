<?php

namespace Test\Unit\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNACredentials;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNASepaPaymentRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class ABNAGatewayTest extends TestCase
{
    private ABNACredentials $credentials;

    public function setUp(): void
    {
        $this->credentials = new ABNACredentials('clientId', 'apiKey', true);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldReturnAccessToken(): void
    {
        $client = new Client();
        $response = new Response(200, [], $this->getAccessTokenResponse());
        $client->addResponse($response);
        $gateway = new ABNAGateway($client, $this->credentials);
        $accessToken = $gateway->createAccessToken('scope');

        $this->assertEquals('0003mBb4xxDCqNxnyS4JmAp8dazy', $accessToken);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testExpectExceptionWhenAccessTokenMissing(): void
    {
        $client = new Client();
        $response = new Response(200, [], json_encode([]));
        $client->addResponse($response);
        $gateway = new ABNAGateway($client, $this->credentials);

        $this->expectException(ClientExceptionInterface::class);
        $gateway->createAccessToken('scope');
    }

    public function testSepaPaymentExceptionWhenTransactionIdMissing(): void
    {
        $client = new Client();
        $accessTokenResponse = new Response(200, [], $this->getAccessTokenResponse());
        $client->addResponse($accessTokenResponse);
        $sepaPaymentResponse = new Response(200, [], json_encode(['status' => 'STORED']));
        $client->addResponse($sepaPaymentResponse);
        $gateway = new ABNAGateway($client, $this->credentials);
        $sepaRequest = new ABNASepaPaymentRequest('iban', 'Nikos Rigas', 10);

        $this->expectException(ClientExceptionInterface::class);
        $gateway->sepaPayment($sepaRequest);
    }

    public function testSepaPaymentExceptionWhenStatusIsNotSuccess(): void
    {
        $client = new Client();
        $accessTokenResponse = new Response(200, [], $this->getAccessTokenResponse());
        $client->addResponse($accessTokenResponse);
        $sepaPaymentResponse = new Response(200, [], json_encode([
            'transactionId' => 'dwxcefgve',
            'status' => 'REJECTED'
        ]));
        $client->addResponse($sepaPaymentResponse);
        $gateway = new ABNAGateway($client, $this->credentials);
        $sepaRequest = new ABNASepaPaymentRequest('iban', 'Nikos Rigas', 10);

        $this->expectException(ClientExceptionInterface::class);
        $gateway->sepaPayment($sepaRequest);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSepaPaymentResponseWillHasTransactionId(): void
    {
        $client = new Client();
        $accessTokenResponse = new Response(200, [], $this->getAccessTokenResponse());
        $client->addResponse($accessTokenResponse);
        $sepaPaymentResponse = new Response(200, [], $this->getSuccessfulSepaPaymentResponse());
        $client->addResponse($sepaPaymentResponse);
        $gateway = new ABNAGateway($client, $this->credentials);
        $sepaRequest = new ABNASepaPaymentRequest('iban', 'Nikos Rigas', 10);
        $response = $gateway->sepaPayment($sepaRequest);

        $this->assertEquals('VS8BVLWKFJ1653162174254', $response->transactionId);
    }

    private function getSuccessfulSepaPaymentResponse(): string
    {
        return '{"transactionId":"VS8BVLWKFJ1653162174254","status":"STORED"}';
    }

    private function getAccessTokenResponse(): string
    {
        return '{"access_token":"0003mBb4xxDCqNxnyS4JmAp8dazy","token_type":"Bearer","expires_in":7200}';
    }
}
