<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetMerchantBankAccounts;

use Exception;
use PayByBank\Domain\Repository\BankAccountRepository;

final class GetMerchantBankAccountsUseCase
{
    private BankAccountRepository $bankAccountRepository;

    public function __construct(BankAccountRepository $bankAccountRepository)
    {
        $this->bankAccountRepository = $bankAccountRepository;
    }

    /**
     * @throws Exception
     */
    public function get(
        GetMerchantBankAccountsRequest $request,
        GetMerchantBankAccountsPresenter $presenter
    ): void {
        $bankAccounts = $this->bankAccountRepository->findAllByMerchantId(
            $request->merchantId
        );

        if (!$bankAccounts) {
            throw new Exception('Merchant does not have bank accounts');
        }

        $presenter->present($bankAccounts);
    }
}
