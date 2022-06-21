<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\GetPaymentOrderIframe;

use Larium\Bridge\Template\Template;
use Larium\Bridge\Template\TwigTemplate;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\GetPaymentOrderIframe\GetPaymentOrderIframeAction;
use Test\Unit\WebApi\Actions\ActionTestCase;

class GetPaymentOrderIframeActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly GetPaymentOrderBankAccountsUseCase $getMerchantBankAccountsUseCase;

    private readonly Template $template;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->template = $this->createMock(Template::class);

        $this->getMerchantBankAccountsUseCase = new GetPaymentOrderBankAccountsUseCase(
            $this->bankAccountRepository,
            $this->paymentOrderRepository
        );
    }

    public function testExpectBadRequestWhenPaymentOrderTokenMissing(): void
    {
        $action = new GetPaymentOrderIframeAction(
            $this->getMerchantBankAccountsUseCase,
            $this->template
        );
        $response = $action($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenPaymentOrderIsInvalid(): void
    {
        $action = new GetPaymentOrderIframeAction(
            $this->getMerchantBankAccountsUseCase,
            $this->template
        );
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWhenMerchantHasNoAccounts(): void
    {
        $this->paymentOrderRepository->method('findByToken')->willReturn(new PaymentOrder(10, ''));
        $action = new GetPaymentOrderIframeAction(
            $this->getMerchantBankAccountsUseCase,
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
        $action = new GetPaymentOrderIframeAction(
            $this->getMerchantBankAccountsUseCase,
            $this->template
        );
        $serverRequest = $this->mockServerRequestWithAttribute('token');
        $response = $action($serverRequest);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
