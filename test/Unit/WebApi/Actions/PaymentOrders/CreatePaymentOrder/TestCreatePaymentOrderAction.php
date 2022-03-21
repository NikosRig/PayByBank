<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\PaymentOrders\CreatePaymentOrder;

use Iban\Validation\Validator;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\IPaymentOrderStoreRepository;
use PayByBank\WebApi\Actions\PaymentOrders\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\PaymentOrders\CreatePaymentOrder\CreatePaymentOrderParamsValidator;
use PayByBank\WebApi\Modules\Validation\RequestParamsValidator;
use PHPUnit\Framework\TestCase;
use Test\Unit\WebApi\TestHelpers\ServerRequestMocker;

class TestCreatePaymentOrderAction extends TestCase
{
    public function setUp(): void
    {
        $this->validator = new CreatePaymentOrderParamsValidator(new Validator());
        $this->paymentOrderStoreRepository = $this->createMock(IPaymentOrderStoreRepository::class);
    }

    public function testAssertCreditorIbanIsRequired(): void
    {
        $requestBody = json_encode([
           'amount' => 10,
           'creditorName' => 'Test'
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertNumericCreditorIbanIsNotAccepted(): void
    {
        $requestBody = json_encode([
            'creditorIban' => 1,
            'amount' => 10,
            'creditorName' => 'Test'
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertAmountIsRequired(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'creditorName' => 'Test'
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertStringAmountIsNotAccepted(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 1,
           'amount' => '10',
           'creditorName' => 'Test'
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertCreditorNameIsRequired(): void
    {
        $requestBody = json_encode([
           'amount' => 10,
           'creditorIban' => 'GR2101422757743955519929399',
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertNumericCreditorNameIsNotAccepted(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'amount' => 10,
           'creditorName' => 1
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertNumericBankIsNotAccepted(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'amount' => 10,
           'creditorName' => 'John Doe',
           'bank' => 1
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new CreatePaymentOrderAction(
            $this->createMock(CreatePaymentOrderUseCase::class),
            new RequestParamsValidator()
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }
}
