<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

class CreateMerchantPresenter
{
    public readonly string $mid;

    public function present(string $mid): void
    {
        $this->mid = $mid;
    }
}
