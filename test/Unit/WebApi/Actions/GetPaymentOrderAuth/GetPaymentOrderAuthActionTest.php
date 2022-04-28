<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\GetPaymentOrderAuth;

use GuzzleHttp\Psr7\ServerRequest;
use PayByBank\WebApi\Actions\GetPaymentOrderAuth\GetPaymentOrderAuthAction;
use PHPUnit\Framework\TestCase;

class GetPaymentOrderAuthActionTest extends TestCase
{
    public function testAssertParseOfPaymentOrderToken(): void
    {
        $action = new GetPaymentOrderAuthAction();
    }

    public function testAssertErrorViewOnAuthorizedPaymentOrder(): void
    {
    }

    public function testAssertErrorViewOnExpiredPaymentOrder(): void
    {
    }

    public function testAssertBankView(): void
    {
        $request = new ServerRequest('GET', 'paybybank.com/order/:token/authorize');

        $action = new GetPaymentOrderAuthAction();

        $action($request);
    }
}
