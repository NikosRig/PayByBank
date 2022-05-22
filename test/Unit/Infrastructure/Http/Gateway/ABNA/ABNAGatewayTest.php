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

    public function testAssertExceptionWhenCodeFailedToBeAuthorized(): void
    {
        $this->client->addResponse($this->getCodeFailedToBeAuthorizedResponse());
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->authorizeCode('code', 'accessToken');
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertCodeWillBeAuthorized(): void
    {
        $this->client->addResponse($this->getCodeAuthorizedResponse());
        $response = $this->gateway->authorizeCode('code', 'accessToken');

        $this->assertIsString($response->accessToken);
        $this->assertIsString($response->refreshToken);
        $this->assertIsInt($response->expiresIn);
    }

    public function testAssertSepaPaymentExceptionWhenTransactionStatusIsNotCorrect(): void
    {
        $this->client->addResponse($this->getSepaPaymentFailedResponse());
        $this->expectException(ClientExceptionInterface::class);

        $this->gateway->executeSepaPayment('accessToken', 'transactionId');
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertSuccessfulSepaPaymentExecution(): void
    {
        $this->client->addResponse($this->getSepaPaymentExecutedResponse());
        $this->gateway->executeSepaPayment('accessToken', 'transactionId');
        $this->assertTrue(true);
    }

    private function getSepaPaymentFailedResponse(): ResponseInterface
    {
        $responseBody = '{
            	"errors": [
                	{
                	    "code": "MESSAGE_BAI561_0067",
                		"message": "Mismatch TransactionID call and token",
                		"reference": "https://developer.abnamro.com/api-products/payment-initiation-psd2/reference-documentation",
                		"traceId": "1b6ffcf0-1aed-4505-add4-3ae03d1b822e",
                	    "status": 403,
                		"category":"BACKEND_ERROR"
                	}
            	]
            }';
        return new Response(200, [], $responseBody);
    }

    private function getSepaPaymentExecutedResponse(): ResponseInterface
    {
        $responseBody = '{"transactionId":"DRODWIC7M31653242065780","status":"EXECUTED"}';
        return new Response(200, [], $responseBody);
    }

    private function getCodeFailedToBeAuthorizedResponse(): ResponseInterface
    {
        $responseBody = '{"error_description":"Authorization code is invalid or expired.","error":"invalid_grant"}';
        return new Response(200, [], $responseBody);
    }

    private function getCodeAuthorizedResponse(): ResponseInterface
    {
        $responseBody = '{"access_token":"0003LV10KauEKsdnlwiIo9yJE90t","refresh_token":"wtHdJHLTNuV5miaKruUGDwAvoxuR0CB8z4DGDcnevo","token_type":"Bearer","expires_in":7200}';
        return new Response(200, [], $responseBody);
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
