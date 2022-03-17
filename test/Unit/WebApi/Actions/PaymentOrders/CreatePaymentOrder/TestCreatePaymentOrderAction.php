<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\PaymentOrders\CreatePaymentOrder;

use PayByBank\Domain\Repository\IPaymentOrderStoreRepository;
use PayByBank\WebApi\Actions\PaymentOrders\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Modules\RequestValidator;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Validator;
use Test\Unit\WebApi\TestHelpers\ServerRequestMocker;

class TestCreatePaymentOrderAction extends TestCase
{
    private RequestValidator $requestValidator;

    public function setUp(): void
    {
        $this->requestValidator = new RequestValidator(new Validator());
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
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
            $this->requestValidator,
            $this->paymentOrderStoreRepository
        );
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }
}
