<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderPresenter;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderRequest;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderUseCase;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class GetPaymentOrderUseCaseTest extends TestCase
{
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn(null);
        $useCase = new GetPaymentOrderUseCase($repository);

        $this->expectException(InvalidArgumentException::class);
        $request = new GetPaymentOrderRequest('pmt_ord_dpwij134dpldeijh');
        $presenter = new GetPaymentOrderPresenter();
        $useCase->get($request, $presenter);
    }

    public function testSuccessfulGetPaymentOrderResponse(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn(new PaymentOrder(10));
        $useCase = new GetPaymentOrderUseCase($repository);
        $request = new GetPaymentOrderRequest('pmt_ord_success');
        $presenter = new GetPaymentOrderPresenter();
        $useCase->get($request, $presenter);

        $this->assertIsString($presenter->paymentOrderToken);
        $this->assertIsInt($presenter->amount);
    }
}
