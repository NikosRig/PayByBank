<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetPaymentOrderBankAccounts;

use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsPresenter;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsRequest;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class GetPaymentOrderBankAccountsUseCaseTest extends TestCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly GetPaymentOrderBankAccountsUseCase $useCase;

    private readonly PaymentOrderRepository $paymentOrderRepository;

    public function setUp(): void
    {
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->useCase = new GetPaymentOrderBankAccountsUseCase(
            $this->bankAccountRepository,
            $this->paymentOrderRepository
        );
    }

    public function testExpectExceptionWhenMerchantHasNoBankAccounts(): void
    {
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturn(null);
        $request = new GetPaymentOrderBankAccountsRequest('');
        $presenter = new GetPaymentOrderBankAccountsPresenter();
        $this->expectException(Exception::class);

        $this->useCase->get($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testPresenterShouldHasBankAccount(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturnCallback(function () {
            $bankAccounts = [];
            $bankAccounts[] = $this->createMock(BankAccount::class);

            return $bankAccounts;
        });
        $request = new GetPaymentOrderBankAccountsRequest('');
        $presenter = new GetPaymentOrderBankAccountsPresenter();
        $this->useCase->get($request, $presenter);

        $this->assertNotEmpty($presenter->bankAccounts);
    }

    /**
     * @throws Exception
     */
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(null);
        $request = new GetPaymentOrderBankAccountsRequest('');
        $presenter = new GetPaymentOrderBankAccountsPresenter();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->get($request, $presenter);
    }
}
