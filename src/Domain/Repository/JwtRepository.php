<?php

declare(strict_types=1);

namespace PayByBank\Domain\Repository;

use PayByBank\Domain\Entity\Jwt;

interface JwtRepository
{
    public function save(Jwt $jwt): void;
}