<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetMerchantBankAccounts;

class GetMerchantBankAccountsPresenter
{
    public readonly ?array $bankAccounts;

    public function present(array $bankAccounts): void
    {
        $this->bankAccounts = $bankAccounts;
    }
}
