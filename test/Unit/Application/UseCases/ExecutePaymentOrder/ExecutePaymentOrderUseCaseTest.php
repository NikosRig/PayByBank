<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\ExecutePaymentOrder;

use DateTime;
use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlUseCase;
use PayByBank\Application\UseCases\ExecutePaymentOrder\ExecutePaymentOrderRequest;
use PayByBank\Application\UseCases\ExecutePaymentOrder\ExecutePaymentOrderUseCase;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Domain\ValueObjects\TransactionState;
use PHPUnit\Framework\TestCase;

class ExecutePaymentOrderUseCaseTest extends TestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountsRepository;

    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly ExecutePaymentOrderUseCase $useCase;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountsRepository = $this->createMock(BankAccountRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->useCase = new ExecutePaymentOrderUseCase(
            $this->transactionRepository,
            $this->paymentOrderRepository,
            $this->bankAccountsRepository,
            $this->paymentMethodResolver
        );
    }

    /**
     * @throws Exception
     */
    public function testExpectExceptionWhenPaymentOrderIsNotAuthorized(): void
    {
        $this->transactionRepository->method('findByTransactionId')->willReturn(
            $this->createTransaction()
        );
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, 'mid'));
        $request = new ExecutePaymentOrderRequest('transactionId', 'authCode');
        $this->expectException(Exception::class);
        $this->useCase->execute($request);
    }

    private function createTransaction(): Transaction
    {
        $state = new TransactionState(
            '1',
            new DateTime(),
            '1',
            'paymentOrderToken',
            'scaRedirectUrl',
            '123',
            '127.0.0.1',
            []
        );

        return Transaction::fromState($state);
    }
}
