<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreatePaymentOrder;

use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderValidatorBuilder;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreatePaymentOrderActionTest extends ActionTestCase
{
    public function testAssertCreditorIbanIsRequired(): void
    {
        $requestBody = json_encode([
           'amount' => 10,
           'creditorName' => 'Test'
        ]);

        $request = $this->mockServerRequest($requestBody);
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
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
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertAmountIsRequired(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'creditorName' => 'Test'
        ]);
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
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
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertCreditorNameIsRequired(): void
    {
        $requestBody = json_encode([
           'amount' => 10,
           'creditorIban' => 'GR2101422757743955519929399',
        ]);
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
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
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
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
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }

    public function testAssertErrorWithNonJsonBody(): void
    {
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $controller = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response = json_decode($controller($this->mockServerRequest()));

        $this->assertObjectHasAttribute('error', $response);
    }
}
