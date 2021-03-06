<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\PaymentMethods;

use Exception;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\PaymentMethod;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

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
    public function createScaRedirectUrl(Transaction $transaction): void
    {
        $request = new RegisterSepaPaymentRequest(
            $transaction->getCreditorIban(),
            $transaction->getCreditorName(),
            $transaction->getAmount(),
        );
        try {
            $response = $this->gateway->registerSepaPayment($request);
            $transaction->updateScaInfo($response->scaRedirectUrl);
        } catch (ClientExceptionInterface $e) {
            throw new Exception('Sca redirect url failed to be created.');
        }
    }

    public function getBankCode(): string
    {
        return 'ABNA';
    }
}
