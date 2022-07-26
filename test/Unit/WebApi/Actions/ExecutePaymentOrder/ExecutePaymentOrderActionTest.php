<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\ExecutePaymentOrder;

use PayByBank\Application\UseCases\ExecutePaymentOrder\ExecutePaymentOrderUseCase;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\WebApi\Actions\ExecutePaymentOrder\ExecutePaymentOrderAction;
use PayByBank\WebApi\Actions\ExecutePaymentOrder\ExecutePaymentOrderValidatorBuilder;
use Psr\Log\LoggerInterface;
use Test\Unit\WebApi\Actions\ActionTestCase;

class ExecutePaymentOrderActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly ExecutePaymentOrderUseCase $useCase;

    private readonly LoggerInterface $logger;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->useCase = new ExecutePaymentOrderUseCase(
            $this->transactionRepository,
            $this->paymentOrderRepository,
            $this->bankAccountRepository,
            $this->paymentMethodResolver
        );
    }

    public function testExpectBadRequestWhenTransactionIdMissing(): void
    {
        $action = new ExecutePaymentOrderAction(
            $this->useCase,
            $this->logger,
            new ExecutePaymentOrderValidatorBuilder()
        );
        $response = $action($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }
}
