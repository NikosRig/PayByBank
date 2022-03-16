<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

class CreatePaymentOrderUseCase
{
    public function __invoke(string $creditorIban, string $creditorName, int $amount): string
    {

    }
}
