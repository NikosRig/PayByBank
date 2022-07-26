<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreateScaRedirectUrl;

use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlUseCase;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\WebApi\Actions\CreateScaRedirectUrl\CreateScaRedirectUrlAction;
use PayByBank\WebApi\Actions\CreateScaRedirectUrl\CreateScaRedirectUrlValidatorBuilder;
use Psr\Log\LoggerInterface;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateScaRedirectUrlActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly TransactionRepository $transactionRepository;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly LoggerInterface $logger;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->useCase = new CreateScaRedirectUrlUseCase(
            $this->paymentOrderRepository,
            $this->bankAccountRepository,
            $this->transactionRepository,
            $this->paymentMethodResolver
        );
    }

    public function testExpectBadRequestWhenPaymentOrderTokenMissing(): void
    {
        $action = new CreateScaRedirectUrlAction(
            $this->useCase,
            new CreateScaRedirectUrlValidatorBuilder(),
            $this->logger
        );
        $requestBody = json_encode([
            'bankCode' => 'bankCode',
            'psuIp' => '127.0.0.1'
        ]);
        $serverRequest = $this->mockServerRequest($requestBody);
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenPsuIpIsInvalid(): void
    {
        $action = new CreateScaRedirectUrlAction(
            $this->useCase,
            new CreateScaRedirectUrlValidatorBuilder(),
            $this->logger
        );
        $requestBody = json_encode([
            'bankCode' => 'bankCode',
            'psuIp' => 'invalid-ip'
        ]);
        $serverRequest = $this->mockServerRequest($requestBody);
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
