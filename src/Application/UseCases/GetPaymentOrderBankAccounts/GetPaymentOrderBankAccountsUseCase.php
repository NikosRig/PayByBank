<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderBankAccounts;

use Exception;
use InvalidArgumentException;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class GetPaymentOrderBankAccountsUseCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly PaymentOrderRepository $paymentOrderRepository;

    public function __construct(
        BankAccountRepository $bankAccountRepository,
        PaymentOrderRepository $paymentOrderRepository
    ) {
        $this->bankAccountRepository = $bankAccountRepository;
        $this->paymentOrderRepository = $paymentOrderRepository;
    }

    /**
     * @throws Exception
     */
    public function get(
        GetPaymentOrderBankAccountsRequest   $request,
        GetPaymentOrderBankAccountsPresenter $presenter
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

        $presenter->present($bankAccounts);
    }
}
