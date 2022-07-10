<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentMethods;

use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsPresenter;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsRequest;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethod;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class GetPaymentMethodsUseCaseTest extends TestCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly GetPaymentMethodsUseCase $useCase;

    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    public function setUp(): void
    {
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->useCase = new GetPaymentMethodsUseCase(
            $this->bankAccountRepository,
            $this->paymentOrderRepository,
            $this->paymentMethodResolver
        );
    }

    public function testExpectExceptionWhenMerchantHasNoBankAccounts(): void
    {
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturn(null);
        $request = new GetPaymentMethodsRequest('');
        $presenter = new GetPaymentMethodsPresenter();
        $this->expectException(Exception::class);

        $this->useCase->get($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testPresenterShouldHasPaymentMethod(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturnCallback(function () {
            $bankAccounts = [];
            $bankAccounts[] = $this->createMock(BankAccount::class);

            return $bankAccounts;
        });
        $this->paymentMethodResolver->method('resolve')->willReturn(
            $this->mockPaymentMethod()
        );
        $request = new GetPaymentMethodsRequest('');
        $presenter = new GetPaymentMethodsPresenter();
        $this->useCase->get($request, $presenter);

        $this->assertNotEmpty($presenter->paymentMethods);
    }

    /**
     * @throws Exception
     */
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(null);
        $request = new GetPaymentMethodsRequest('');
        $presenter = new GetPaymentMethodsPresenter();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->get($request, $presenter);
    }

    private function mockPaymentMethod(): PaymentMethod
    {
        $paymentMethod = $this->createMock(PaymentMethod::class);
        $paymentMethod->method('getBankCode')->willReturn('BankCode');

        return $paymentMethod;
    }
}
