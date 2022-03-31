<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderStoreRepository;
use PayByBank\Domain\ValueObjects\CreditorAccount;

class CreatePaymentOrderUseCase
{
    private PaymentOrderStoreRepository $paymentOrderStoreRepository;

    public function __construct(PaymentOrderStoreRepository $paymentOrderStoreRepository)
    {
        $this->paymentOrderStoreRepository = $paymentOrderStoreRepository;
    }

    public function create(string $creditorIban, string $creditorName, int $amount, string $bank): string
    {
        $creditorAccount = new CreditorAccount($creditorIban, $creditorName);
        $paymentOrder = new PaymentOrder($creditorAccount, $amount, $bank);
        $this->paymentOrderStoreRepository->store($paymentOrder);

        return $paymentOrder->getToken();
    }
}
