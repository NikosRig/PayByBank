<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\AccessToken;

interface AccessTokenRepository
{
    public function findByToken(string $token): ?AccessToken;

    public function save(AccessToken $accessToken): void;
}
