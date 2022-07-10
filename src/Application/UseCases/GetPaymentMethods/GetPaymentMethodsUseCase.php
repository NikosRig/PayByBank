<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentMethods;

use Exception;
use InvalidArgumentException;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class GetPaymentMethodsUseCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    public function __construct(
        BankAccountRepository $bankAccountRepository,
        PaymentOrderRepository $paymentOrderRepository,
        PaymentMethodResolver $paymentMethodResolver
    ) {
        $this->bankAccountRepository = $bankAccountRepository;
        $this->paymentOrderRepository = $paymentOrderRepository;
        $this->paymentMethodResolver = $paymentMethodResolver;
    }

    /**
     * @throws Exception
     */
    public function get(
        GetPaymentMethodsRequest   $request,
        GetPaymentMethodsPresenter $presenter
    ): void {
        $paymentOrder = $this->paymentOrderRepository->findByToken($request->paymentOrderToken);

        if (!$paymentOrder || $paymentOrder->hasExpired()) {
            throw new InvalidArgumentException('Invalid payment order token.');
        }

        $bankAccounts = $this->bankAccountRepository->findAllByMerchantId(
            $paymentOrder->getMerchantId()
        );

        if (!$bankAccounts) {
            throw new Exception('Merchant does not have bank accounts.');
        }

        $paymentMethods = [];
        foreach ($bankAccounts as $bankAccount) {
            $paymentMethods[] = $this->paymentMethodResolver->resolve($bankAccount);
        }

        $presenter->present($paymentMethods);
    }
}
