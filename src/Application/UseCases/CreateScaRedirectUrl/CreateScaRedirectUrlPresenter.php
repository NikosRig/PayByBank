<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateScaRedirectUrl;

class CreateScaRedirectUrlPresenter
{
    public readonly string $scaRedirectUrl;

    public function present(string $scaRedirectUrl): void
    {
        $this->scaRedirectUrl = $scaRedirectUrl;
    }
}
