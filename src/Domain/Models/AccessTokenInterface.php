<?php

declare(strict_types=1);

namespace PayByBank\Domain\Models;

interface AccessTokenInterface
{
    public function getToken(): string;

    public function getExpires(): int;

    public function getScope(): string;
}