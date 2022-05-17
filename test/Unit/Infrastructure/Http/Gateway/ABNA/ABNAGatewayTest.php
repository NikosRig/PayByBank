<?php

namespace Test\Unit\Infrastructure\Http\Gateway\ABNA;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAScope;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class ABNAGatewayTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testShouldReturnAccessToken(): void
    {
        $client = new Client();
        $response = new Response(200, [], $this->getAccessTokenResponse());
        $client->addResponse($response);
        $gateway = new ABNAGateway($client);
        $accessToken = $gateway->createAccessToken('TPP_test', ABNAScope::SEPA_PAYMENT);

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
        $gateway = new ABNAGateway($client);

        $this->expectException(ClientExceptionInterface::class);
        $gateway->createAccessToken('TPP_test', ABNAScope::SEPA_PAYMENT);
    }

    private function getAccessTokenResponse(): string
    {
        return '{"access_token":"0003mBb4xxDCqNxnyS4JmAp8dazy","token_type":"Bearer","expires_in":7200}';
    }
}
