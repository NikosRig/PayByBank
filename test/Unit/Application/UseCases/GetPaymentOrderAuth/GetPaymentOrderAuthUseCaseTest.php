<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentOrderAuth;

use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthResponse;
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
        $useCase->get('pmt_ord_dpwij134dpldeijh');
    }

    public function testSuccessfulGetPaymentOrderResponse(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn($this->makePaymentOrder());
        $useCase = new GetPaymentOrderAuthUseCase($repository);
        $response = $useCase->get('pmt_ord_success');

        $this->assertInstanceOf(GetPaymentOrderAuthResponse::class, $response);
    }

    public function makePaymentOrder(): PaymentOrder
    {
        $creditorAccount = new CreditorAccount('NL76ABNA9406362538', 'Nikos Rigas');

        return new PaymentOrder($creditorAccount, 10, 'ING');
    }
}
