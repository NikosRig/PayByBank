<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\ValueObjects\CreditorAccount;

final class CreatePaymentOrderUseCase
{
    private PaymentOrderRepository $paymentOrderRepository;

    public function __construct(PaymentOrderRepository $paymentOrderRepository)
    {
        $this->paymentOrderRepository = $paymentOrderRepository;
    }

    public function create(CreatePaymentOrderInput $input): CreatePaymentOrderOutput
    {
        $creditorAccount = new CreditorAccount($input->creditorIban, $input->creditorName);
        $paymentOrder = new PaymentOrder($creditorAccount, $input->amount, $input->bank);
        $this->paymentOrderRepository->save($paymentOrder);

        return new CreatePaymentOrderOutput($paymentOrder->getToken());
    }
}
