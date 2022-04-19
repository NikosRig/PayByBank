<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentOrderAuth;

use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthInput;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthOutput;
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
        $input = new GetPaymentOrderAuthInput('pmt_ord_dpwij134dpldeijh');
        $useCase->get($input);
    }

    public function testSuccessfulGetPaymentOrderResponse(): void
    {
        $repository = $this->createMock(PaymentOrderRepository::class);
        $repository->method('findByToken')->willReturn($this->makePaymentOrder());
        $useCase = new GetPaymentOrderAuthUseCase($repository);
        $input = new GetPaymentOrderAuthInput('pmt_ord_success');
        $output = $useCase->get($input);

        $this->assertInstanceOf(GetPaymentOrderAuthOutput::class, $output);
    }

    public function makePaymentOrder(): PaymentOrder
    {
        $creditorAccount = new CreditorAccount('NL76ABNA9406362538', 'Nikos Rigas');

        return new PaymentOrder($creditorAccount, 10, 'ING');
    }
}
