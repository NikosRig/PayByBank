<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateAccessToken;

class CreateAccessTokenPresenter
{
    public readonly string $token;

    public function present(string $token): void
    {
        $this->token = $token;
    }
}
