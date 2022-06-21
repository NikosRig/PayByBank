<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderBankAccounts;

class GetPaymentOrderBankAccountsPresenter
{
    public readonly ?array $bankAccounts;

    public function present(array $bankAccounts): void
    {
        $this->bankAccounts = $bankAccounts;
    }
}
