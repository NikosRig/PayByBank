<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreatePaymentOrder;

use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
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
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
    }

    public function testAssertNumericBankIsNotAccepted(): void
    {
        $requestBody = json_encode([
           'creditorIban' => 'GR2101422757743955519929399',
           'amount' => 10,
           'creditorName' => 'John Doe',
           'bank' => 1
        ]);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response = $action($this->mockServerRequest($requestBody));

        $this->assertResponseIsJsonAndHasError($response);
    }

    public function testAssertErrorWithNonJsonBody(): void
    {
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->createMock(PaymentOrderRepository::class)
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response =  $action($this->mockServerRequest());

        $this->assertResponseIsJsonAndHasError($response);
    }

    private function assertResponseIsJsonAndHasError(ResponseInterface $response): void
    {
        $responseBody = $response->getBody()->getContents();
        $this->assertJson($responseBody);
        $this->assertObjectHasAttribute('error', json_decode($responseBody));
    }
}
