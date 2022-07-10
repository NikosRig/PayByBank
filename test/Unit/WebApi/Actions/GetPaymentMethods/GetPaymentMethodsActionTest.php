<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\GetPaymentMethods;

use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\GetPaymentMethods\GetPaymentMethodsAction;
use Test\Unit\WebApi\Actions\ActionTestCase;

class GetPaymentMethodsActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly GetPaymentMethodsUseCase $getMerchantBankAccountsUseCase;

    private readonly Template $template;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->template = $this->createMock(Template::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);

        $this->useCase = new GetPaymentMethodsUseCase(
            $this->bankAccountRepository,
            $this->paymentOrderRepository,
            $this->paymentMethodResolver
        );
    }

    public function testExpectBadRequestWhenPaymentOrderTokenMissing(): void
    {
        $action = new GetPaymentMethodsAction(
            $this->useCase,
            $this->template
        );
        $response = $action($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenPaymentOrderIsInvalid(): void
    {
        $action = new GetPaymentMethodsAction(
            $this->useCase,
            $this->template
        );
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenMerchantHasNoAccounts(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $action = new GetPaymentMethodsAction(
            $this->useCase,
            $this->template
        );
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertSuccessfulResponse(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturn([
            new BankAccount('', '', '', '')
        ]);
        $action = new GetPaymentMethodsAction(
            $this->useCase,
            $this->template
        );
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
