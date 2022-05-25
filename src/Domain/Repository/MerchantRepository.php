<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\Merchant;

interface MerchantRepository
{
    public function findByMid(string $mid): ?Merchant;

    public function save(Merchant $merchant): void;
}
