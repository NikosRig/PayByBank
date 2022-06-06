<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreatePaymentOrder;

use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderRequest;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class CreatePaymentOrderUseCaseTest extends TestCase
{
    public function testShouldSavePaymentOrder(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->expects($this->once())->method('save');
        $useCase = new CreatePaymentOrderUseCase($repository);

        $request = new CreatePaymentOrderRequest(10);
        $presenter = new CreatePaymentOrderPresenter();
        $useCase->create($request, $presenter);
    }

    public function testAssertPresenterHasPaymentOrderToken(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $useCase = new CreatePaymentOrderUseCase($repository);

        $request = new CreatePaymentOrderRequest(10);
        $presenter = new CreatePaymentOrderPresenter();
        $useCase->create($request, $presenter);

        $this->assertIsString($presenter->getPaymentOrderToken());
    }
}
