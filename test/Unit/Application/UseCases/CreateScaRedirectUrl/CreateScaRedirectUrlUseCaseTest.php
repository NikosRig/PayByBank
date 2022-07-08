<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateScaRedirectUrl;

use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlPresenter;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlRequest;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\Http\PaymentMethod;
use PayByBank\Domain\Http\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PHPUnit\Framework\TestCase;

class CreateScaRedirectUrlUseCaseTest extends TestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountsRepository;

    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly CreateScaRedirectUrlUseCase $useCase;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountsRepository = $this->createMock(BankAccountRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->useCase = new CreateScaRedirectUrlUseCase(
            $this->paymentOrderRepository,
            $this->bankAccountsRepository,
            $this->transactionRepository,
            $this->paymentMethodResolver
        );
    }

    /**
     * @throws Exception
     */
    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(null);
        $request = new CreateScaRedirectUrlRequest('token', '010', 'ip');
        $presenter = new CreateScaRedirectUrlPresenter();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->create($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertExceptionWhenBankAccountCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(
            $this->createPaymentOrder()
        );
        $this->bankAccountsRepository->method('findByBankCodeAndMerchantId')->willReturn(null);
        $request = new CreateScaRedirectUrlRequest('token', '010', 'ip');
        $presenter = new CreateScaRedirectUrlPresenter();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->create($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertExceptionWhenPaymentMethodCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(
            $this->createPaymentOrder()
        );
        $this->bankAccountsRepository->method('findByBankCodeAndMerchantId')->willReturn(
            $this->createBankAccount()
        );
        $this->paymentMethodResolver->method('resolveWithBankCode')->willThrowException(new InvalidArgumentException());
        $request = new CreateScaRedirectUrlRequest('token', '010', 'ip');
        $presenter = new CreateScaRedirectUrlPresenter();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->create($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testExpectExceptionWhenTransactionHasNoScaInfo(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(
            $this->createPaymentOrder()
        );
        $this->bankAccountsRepository->method('findByBankCodeAndMerchantId')->willReturn(
            $this->createBankAccount()
        );
        $request = new CreateScaRedirectUrlRequest('token', '010', 'ip');
        $presenter = new CreateScaRedirectUrlPresenter();
        $this->expectException(Exception::class);

        $this->useCase->create($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertTransactionWillBeSaved(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(
            $this->createPaymentOrder()
        );
        $this->bankAccountsRepository->method('findByBankCodeAndMerchantId')->willReturn(
            $this->createBankAccount()
        );
        $paymentMethod = $this->createMock(PaymentMethod::class);
        $paymentMethod->method('createScaRedirectUrl')->willReturnCallback(function (Transaction $transaction) {
            $transaction->updateScaInfo('redirect_url');
        });
        $this->paymentMethodResolver->method('resolveWithBankCode')->willReturn($paymentMethod);
        $this->transactionRepository->expects($this->once())->method('save');
        $request = new CreateScaRedirectUrlRequest('token', '010', 'ip');
        $presenter = new CreateScaRedirectUrlPresenter();
        $this->useCase->create($request, $presenter);
    }

    private function createBankAccount(): BankAccount
    {
        return new BankAccount(
            'NL93ABNA6055981262',
            'Nikos Rigas',
            'mid',
            'bank-01'
        );
    }

    private function createPaymentOrder(): PaymentOrder
    {
        return new PaymentOrder(10, 'mid');
    }
}