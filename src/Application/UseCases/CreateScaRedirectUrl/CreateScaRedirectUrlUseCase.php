<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateScaRedirectUrl;

use Exception;
use InvalidArgumentException;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\ValueObjects\Psu;

final class CreateScaRedirectUrlUseCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    public function __construct(
        PaymentOrderRepository $paymentOrderRepository,
        BankAccountRepository $bankAccountRepository,
        TransactionRepository $transactionRepository,
        PaymentMethodResolver $paymentMethodResolver
    ) {
        $this->paymentOrderRepository = $paymentOrderRepository;
        $this->bankAccountRepository = $bankAccountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paymentMethodResolver = $paymentMethodResolver;
    }

    /**
     * @throws Exception|InvalidArgumentException
     */
    public function create(CreateScaRedirectUrlRequest $request, CreateScaRedirectUrlPresenter $presenter): void
    {
        $paymentOrder = $this->paymentOrderRepository->findByToken($request->paymentOrderToken);
        if (!$paymentOrder || $paymentOrder->hasExpired()) {
            throw new InvalidArgumentException('Invalid payment order token');
        }

        $bankAccount = $this->bankAccountRepository->findByBankCodeAndMerchantId(
            $request->bankCode,
            $paymentOrder->getMerchantId()
        );

        if (!$bankAccount) {
            throw new InvalidArgumentException("Bank account cannot be found");
        }

        $psu = new Psu($request->psuIp);
        $transaction = new Transaction($paymentOrder, $psu, $bankAccount);
        $paymentMethod = $this->paymentMethodResolver->resolve($transaction);
        $paymentMethod->createScaRedirectUrl($transaction);

        if (!$transaction->hasScaInfo()) {
            throw new Exception('Transaction has no ScaInfo.');
        }

        $this->transactionRepository->save($transaction);
        $presenter->present($transaction->getScaRedirectUrl());
    }
}
