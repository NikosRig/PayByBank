<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\Checkout;

use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\Checkout\CheckoutUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\PaymentMethodResolver;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\Checkout\CheckoutAction;
use Psr\Log\LoggerInterface;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CheckoutActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly CheckoutUseCase $getMerchantBankAccountsUseCase;

    private readonly Template $template;

    private readonly PaymentMethodResolver $paymentMethodResolver;

    private readonly LoggerInterface $logger;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->template = $this->createMock(Template::class);
        $this->paymentMethodResolver = $this->createMock(PaymentMethodResolver::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->useCase = new CheckoutUseCase(
            $this->bankAccountRepository,
            $this->paymentOrderRepository,
            $this->paymentMethodResolver
        );
    }

    public function testExpectBadRequestWhenPaymentOrderTokenMissing(): void
    {
        $action = new CheckoutAction($this->useCase, $this->template, $this->logger);
        $response = $action($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenPaymentOrderIsInvalid(): void
    {
        $action = new CheckoutAction($this->useCase, $this->template, $this->logger);
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenMerchantHasNoAccounts(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $action = new CheckoutAction($this->useCase, $this->template, $this->logger);
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
        $action = new CheckoutAction($this->useCase, $this->template, $this->logger);
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
