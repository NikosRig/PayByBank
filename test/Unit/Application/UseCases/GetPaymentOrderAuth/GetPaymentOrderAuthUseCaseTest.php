<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentOrderAuth;

use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthPresenter;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthRequest;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthUseCase;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PHPUnit\Framework\TestCase;

class GetPaymentOrderAuthUseCaseTest extends TestCase
{
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn(null);
        $useCase = new GetPaymentOrderAuthUseCase($repository);

        $this->expectException(InvalidArgumentException::class);
        $request = new GetPaymentOrderAuthRequest('pmt_ord_dpwij134dpldeijh');
        $presenter = new GetPaymentOrderAuthPresenter();
        $useCase->get($request, $presenter);
    }

    public function testSuccessfulGetPaymentOrderResponse(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn($this->makePaymentOrder());
        $useCase = new GetPaymentOrderAuthUseCase($repository);
        $request = new GetPaymentOrderAuthRequest('pmt_ord_success');
        $presenter = new GetPaymentOrderAuthPresenter();
        $useCase->get($request, $presenter);

        $this->assertIsString($presenter->getBankName());
    }

    public function makePaymentOrder(): PaymentOrder
    {
        $creditorAccount = new CreditorAccount('NL76ABNA9406362538', 'Nikos Rigas');

        return new PaymentOrder($creditorAccount, 10, 'ING');
    }
}
