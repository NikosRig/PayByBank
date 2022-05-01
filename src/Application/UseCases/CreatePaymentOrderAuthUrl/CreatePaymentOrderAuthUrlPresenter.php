<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl;

class CreatePaymentOrderAuthUrlPresenter
{
    private readonly string $authorizationUrl;

    public function present(string $authorizationUrl): void
    {
        $this->authorizationUrl = $authorizationUrl;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->authorizationUrl;
    }
}
