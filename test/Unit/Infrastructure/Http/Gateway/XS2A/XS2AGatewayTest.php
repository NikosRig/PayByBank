<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Http\Gateway\XS2A;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PayByBank\Infrastructure\Http\Gateway\Exceptions\BadResponseException;
use PayByBank\Infrastructure\Http\Gateway\XS2A\DTO\XS2ASepaPaymentRequest;
use PayByBank\Infrastructure\Http\Gateway\XS2A\XS2AGateway;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class XS2AGatewayTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testExpectBadResponseExceptionWhenResponseHasFormatError(): void
    {
        $mockClient = new Client();
        $response = new Response(201, [], $this->headerFormatErrorResponse());
        $mockClient->addResponse($response);
        $gateway = new XS2AGateway($mockClient, 'http://tpp-redirect');
        $this->expectException(BadResponseException::class);
        $gateway->sepaPayment($this->mockSepaPaymentRequest());
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testAssertGatewayResponseHasParsedAppropriateParams(): void
    {
        $mockClient = new Client();
        $response = new Response(201, [], $this->sepaPaymentCreatedResponse());
        $mockClient->addResponse($response);
        $gateway = new XS2AGateway($mockClient, 'http://tpp-redirect');
        $response = $gateway->sepaPayment($this->mockSepaPaymentRequest());

        $this->assertEquals('http://172.17.0.1/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUX', $response->scaRedirectUrl);
        $this->assertEquals('9kQY_lwYS563MdUXL86zb', $response->paymentId);
        $this->assertEquals('RCVD', $response->transactionStatus);
    }

    private function mockSepaPaymentRequest(): XS2ASepaPaymentRequest
    {
        return new XS2ASepaPaymentRequest(
            'NL15ABNA5883930565',
            'Nikos Rigas',
            'NL35INGB5361018961',
            50,
            '127.0.0.1',
            'tid_123'
        );
    }

    private function headerFormatErrorResponse(): string
    {
        return <<<HEADER_FORMAT_ERROR
        {
           "tppMessages":[
              {
                 "category":"ERROR",
                 "code":"FORMAT_ERROR",
                 "text":"Header 'x-request-id' should not be blank"
              }
           ]
        } 
        HEADER_FORMAT_ERROR;
    }

    private function sepaPaymentCreatedResponse(): string
    {
        return <<<SEPA_PAYMENT_CREATED
            {
               "transactionStatus":"RCVD",
               "paymentId":"9kQY_lwYS563MdUXL86zb",
               "scaMethods":[
                  {
                     "authenticationType":"Mocked Authentication type",
                     "authenticationVersion":"Mocked Authentication version",
                     "authenticationMethodId":"Mocked Authentication id",
                     "name":"Mocked name"
                  }
               ],
               "_links":{
                  "updatePsuAuthentication":{
                     "href":"http://172.17.0.1/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUX"
                  },
                  "scaRedirect":{
                     "href":"http://172.17.0.1/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUX"
                  },
                  "self":{
                     "href":"http://172.17.0.1:8089/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUXL86zbdNb9oR77kg7fFQsNfTCHQN-R8PM5D6tZZ0kEIet8jsIcgftJbETkzvNvu5mZQqWcA==_=_psGLvQpt9Q"
                  },
                  "status":{
                     "href":"http://172.17.0.1:8089/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUXL86zbdNb9oR77kg7fFQsNfTCHQN-R8PM5D6tZZ0kEIet8jsIcgftJbETkzvNvu5mZQqWcA==_=_psGLvQpt9Q/status"
                  },
                  "scaStatus":{
                     "href":"http://172.17.0.1:8089/v1/payments/sepa-credit-transfers/9kQY_lwYS563MdUXL86zbdNb9oR77kg7fFQsNfTCHQN-R8PM5D6tZZ0kEIet8jsIcgftJbETkzvNvu5mZQqWcA==_=_psGLvQpt9Q/authorisations/8f2bfd74-4e40-48aa-a5f2-fc909c736b80"
                  }
               },
               "psuMessage":"mocked Psu message",
               "tppMessages":[
                  {
                     "category":"WARNING",
                     "code":"WARNING",
                     "text":"Mocked tpp message from the bank"
                  }
               ]
            }
        SEPA_PAYMENT_CREATED;
    }
}
