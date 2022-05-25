<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateJwt;

class CreateJwtPresenter
{
    public readonly string $token;

    public function present(string $token): void
    {
        $this->token = $token;
    }
}
