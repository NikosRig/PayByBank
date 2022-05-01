<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\GetPaymentOrderAuth;

class GetPaymentOrderAuthPresenter
{
    private readonly string $bankName;

    public function present(string $bankName): void
    {
        $this->bankName = $bankName;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }
}
