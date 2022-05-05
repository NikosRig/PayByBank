<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\XS2A;

use GuzzleHttp\Psr7\Request;
use PayByBank\Infrastructure\Http\Exceptions\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class XS2AGateway
{
    private ClientInterface $client;

    private string $sandboxHost = 'http://172.17.0.1:8089';

    private string $tppRedirectUrl;

    public function __construct(ClientInterface $client, string $tppRedirectUrl)
    {
        $this->client = $client;
        $this->tppRedirectUrl = $tppRedirectUrl;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sepaPayment(XS2ASepaPaymentRequest $request): XS2ASepaPaymentResponse
    {
        $requestBody = json_encode([
           "endToEndIdentification" => $request->transactionId,
           'debtorAccount' => ['iban' => $request->debtorIban],
           'creditorAccount' => ['iban' => $request->creditorIban],
           'creditorName' => $request->creditorName,
           'instructedAmount' => [
               'amount' => $request->amount,
               'currency' => 'EUR'
           ]
        ]);

        $request = new Request('POST', "{$this->sandboxHost}/v1/payments/sepa-credit-transfers", [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Request-ID' => $request->transactionId,
            'TPP-Redirect-URI' => $this->tppRedirectUrl,
            'TPP-Explicit-Authorisation-Preferred' => 'false',
            'Psu-IP-Address' => $request->psuIp,
            'TPP-Redirect-Preferred' => 'true',
        ], $requestBody);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $responsePayload = json_decode($responseBody);

        if ($response->getStatusCode() !== 201 || !$responsePayload) {
            throw new BadResponseException($responseBody, $response->getStatusCode());
        }

        return new XS2ASepaPaymentResponse(
            scaRedirectUrl: $responsePayload->_links->scaRedirect->href,
            paymentId: $responsePayload->paymentId,
            transactionStatus: $responsePayload->transactionStatus
        );
    }
}
