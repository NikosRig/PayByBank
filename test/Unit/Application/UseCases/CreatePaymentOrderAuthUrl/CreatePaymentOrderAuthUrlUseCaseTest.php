<?php

namespace Test\Unit\Application\UseCases\CreatePaymentOrderAuthUrl;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl\CreatePaymentOrderAuthUrlPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl\CreatePaymentOrderAuthUrlRequest;
use PayByBank\Application\UseCases\CreatePaymentOrderAuthUrl\CreatePaymentOrderAuthUrlUseCase;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Http\BankResolver;
use PayByBank\Domain\Http\Banks\Bank;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PHPUnit\Framework\TestCase;

class CreatePaymentOrderAuthUrlUseCaseTest extends TestCase
{
    private PaymentOrderRepository $paymentOrderRepository;

    private TransactionRepository $transactionRepository;

    private BankResolver $bankResolver;

    private Bank $bank;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->bankResolver = $this->createMock(BankResolver::class);
        $this->bank = $this->createMock(Bank::class);
        $this->useCase = new CreatePaymentOrderAuthUrlUseCase(
            $this->paymentOrderRepository,
            $this->transactionRepository,
            $this->bankResolver
        );
    }

    public function testAssertExceptionWhenPaymentOrderCannotBeFound(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(null);
        $presenter = new CreatePaymentOrderAuthUrlPresenter();
        $request = new CreatePaymentOrderAuthUrlRequest('token', '127.0.0.1');

        $this->expectException(InvalidArgumentException::class);
        $this->useCase->create($request, $presenter);
    }

    public function testAssertTransactionSave(): void
    {
        $this->paymentOrderRepository->method('findByToken')
            ->willReturn($this->makePaymentOrder());
        $this->bankResolver->method('resolveWithName')->willReturn($this->bank);
        $this->transactionRepository->expects($this->once())->method('save');
        $presenter = new CreatePaymentOrderAuthUrlPresenter();
        $request = new CreatePaymentOrderAuthUrlRequest('token', '127.0.0.1');
        $this->useCase->create($request, $presenter);
    }

    public function testAssertPresenterHasAuthorizationUrl(): void
    {
        $authorizationUrl = 'http://authorization_url';
        $this->paymentOrderRepository->method('findByToken')
            ->willReturn($this->makePaymentOrder());
        $this->bank->method('getAuthorizationUrl')->willReturn($authorizationUrl);
        $this->bankResolver->method('resolveWithName')->willReturn($this->bank);
        $presenter = new CreatePaymentOrderAuthUrlPresenter();
        $request = new CreatePaymentOrderAuthUrlRequest('token', '127.0.0.1');
        $this->useCase->create($request, $presenter);

        $this->assertEquals($authorizationUrl, $presenter->getAuthorizationUrl());
    }

    public function makePaymentOrder(): PaymentOrder
    {
        $creditorAccount = new CreditorAccount('NL76ABNA9406362538', 'Nikos Rigas');

        return new PaymentOrder($creditorAccount, 10, 'ING');
    }
}
