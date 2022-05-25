<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateJwt;

class CreateJwtRequest
{
    public readonly string $mid;

    public function __construct(string $mid)
    {
        $this->mid = $mid;
    }
}
