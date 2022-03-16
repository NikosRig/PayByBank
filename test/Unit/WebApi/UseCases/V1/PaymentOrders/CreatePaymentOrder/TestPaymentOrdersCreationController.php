<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\UseCases\V1\PaymentOrders\CreatePaymentOrder;

use PayByBank\WebApi\Modules\RequestValidator;
use PayByBank\WebApi\UseCases\V1\PaymentOrders\CreatePaymentOrder\PaymentOrdersCreationController;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Validator;
use Test\Unit\WebApi\TestHelpers\ServerRequestMocker;

class TestPaymentOrdersCreationController extends TestCase
{
    private RequestValidator $requestValidator;

    public function setUp(): void
    {
        $this->requestValidator = new RequestValidator(new Validator());
    }

    public function testAssertCreditorIbanIsRequired(): void
    {
        $requestBody = json_encode([
           'amount' => 10,
           'creditorName' => 'Test'
        ]);
        $request = ServerRequestMocker::mock($requestBody);
        $controller = new PaymentOrdersCreationController($this->requestValidator);
        $response = json_decode($controller($request));

        $this->assertObjectHasAttribute('error', $response);
    }
}
