<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\ExecutePaymentOrder;

use Exception;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\TransactionData;

final class ExecutePaymentOrderUseCase
{
    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly PaymentOrderRepository $paymentOrderRepository;

    public function __construct(
        TransactionRepository $transactionRepository,
        PaymentOrderRepository $paymentOrderRepository,
        BankAccountRepository $bankAccountRepository,
        PaymentMethodResolver $paymentMethodResolver
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->paymentOrderRepository = $paymentOrderRepository;
        $this->bankAccountRepository = $bankAccountRepository;
        $this->paymentMethodResolver = $paymentMethodResolver;
    }

    /**
     * @throws Exception
     */
    public function execute(ExecutePaymentOrderRequest $request): void
    {
        $transaction = $this->transactionRepository->findByTransactionId($request->transactionId);
        $paymentOrder = $this->paymentOrderRepository->findByToken($transaction->getPaymentOrderToken());

        if (!$paymentOrder || !$paymentOrder->hasExpired()) {
            throw new Exception('Invalid payment order.');
        }
        $bankAccount = $this->bankAccountRepository->findById($transaction->getBankAccountId());
        $paymentMethod = $this->paymentMethodResolver->resolve($bankAccount);
        $transactionData = new TransactionData(
            $transaction->getTransactionId(),
            $transaction->getBankData(),
            $request->authCode
        );
        $paymentMethod->executePayment($transactionData);
        $paymentOrder->markPaid();
        $this->paymentOrderRepository->save($paymentOrder);
    }
}
