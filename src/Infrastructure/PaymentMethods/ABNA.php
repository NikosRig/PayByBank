<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\PaymentMethods;

use Exception;
use PayByBank\Domain\PaymentMethod;
use PayByBank\Domain\ScaTransactionData;
use PayByBank\Domain\TransactionData;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Throwable;

class ABNA implements PaymentMethod
{
    private readonly ABNAGateway $gateway;

    public function __construct(ABNAGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @inheritDoc
     */
    public function createScaRedirectUrl(ScaTransactionData $scaTransactionData): void
    {
        $request = new RegisterSepaPaymentRequest(
            $scaTransactionData->creditorIban,
            $scaTransactionData->creditorName,
            $scaTransactionData->amount / 100,
        );
        try {
            $response = $this->gateway->registerSepaPayment($request);
            $scaTransactionData->addScaInfo($response->scaRedirectUrl, $response->transactionId, [
                'accessToken' => $response->accessToken
            ]);
        } catch (ClientExceptionInterface $e) {
            throw new Exception('Sca redirect url failed to be created.');
        }
    }

    public function executePayment(TransactionData $transactionData): void
    {
        try {
            $authCodeResponse = $this->gateway->authorizeCode(
                $transactionData->authCode,
                $transactionData->bankData['accessToken']
            );
            $this->gateway->executeSepaPayment(
                $authCodeResponse->accessToken,
                $transactionData->transactionId
            );
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getBankCode(): string
    {
        return 'ABNA';
    }
}
