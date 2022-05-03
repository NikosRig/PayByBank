<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl;

use InvalidArgumentException;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\Http\BankResolver;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\ValueObjects\Psu;

final class CreatePaymentOrderAuthUrlUseCase
{
    private PaymentOrderRepository $paymentOrderRepository;

    private BankResolver $bankResolver;

    private TransactionRepository $transactionRepository;

    public function __construct(
        PaymentOrderRepository $paymentOrderRepository,
        TransactionRepository $transactionRepository,
        BankResolver $bankResolver
    ) {
        $this->paymentOrderRepository = $paymentOrderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->bankResolver = $bankResolver;
    }

    public function create(CreatePaymentOrderAuthUrlRequest $request, CreatePaymentOrderAuthUrlPresenter $presenter): void
    {
        $paymentOrder = $this->paymentOrderRepository->findByToken($request->paymentOrderToken);

        if (!$paymentOrder || !$paymentOrder->canBeAuthorized()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }

        $bank = $this->bankResolver->resolveWithName($paymentOrder->getBankName());
        $psu = new Psu($request->psuIpAddress);
        $transaction = new Transaction($paymentOrder, $psu);
        $authorizationUrl = $bank->getAuthorizationUrl($transaction);
        $this->transactionRepository->save($transaction);

        $presenter->present($authorizationUrl);
    }
}
