<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\Merchant;

interface MerchantRepository
{
    public function findByUsername(string $username): ?Merchant;

    public function save(Merchant $merchant): void;
}
