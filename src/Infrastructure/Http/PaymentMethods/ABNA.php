<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\PaymentMethods;

use Exception;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\Http\PaymentMethod;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

class ABNA implements PaymentMethod
{
    private readonly ABNAGateway $gateway;

    private readonly LoggerInterface $logger;

    public function __construct(ABNAGateway $gateway, LoggerInterface $logger)
    {
        $this->gateway = $gateway;
        $this->logger = $logger;
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
            $this->logger->error($e->getMessage());
            throw new Exception('Sca redirect url failed to be created.');
        }
    }

    public function getBankCode(): string
    {
        return 'ABNA';
    }
}
