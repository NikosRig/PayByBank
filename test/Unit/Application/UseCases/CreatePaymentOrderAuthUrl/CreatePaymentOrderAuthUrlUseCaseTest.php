<?php

namespace Test\Unit\Application\UseCases\CreatePaymentOrderAuthUrl;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl\CreatePaymentOrderAuthUrlRequest;
use PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl\CreatePaymentOrderAuthUrlUseCase;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class CreatePaymentOrderAuthUrlUseCaseTest extends TestCase
{
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn(null);
        $useCase = new CreatePaymentOrderAuthUrlUseCase($repository);

        $this->expectException(InvalidArgumentException::class);
        $request = new CreatePaymentOrderAuthUrlRequest('pmt_ord_dpwij134dpldeijh');
        $presenter = new CreatePaymentOrderPresenter();
        $useCase->create($request, $presenter);
    }
}
