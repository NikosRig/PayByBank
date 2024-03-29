<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Http\PaymentMethods;

use Exception;
use PayByBank\Domain\ScaTransactionData;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentResponse;
use PayByBank\Infrastructure\PaymentMethods\ABNA;
use PHPUnit\Framework\TestCase;

class ABNATest extends TestCase
{
    private readonly ABNA $paymentMethod;

    private readonly ABNAGateway $gateway;

    public function setUp(): void
    {
        $this->gateway = $this->createMock(ABNAGateway::class);
        $this->paymentMethod = new ABNA($this->gateway);
    }

    /**
     * @throws Exception
     */
    public function testShouldHasScaInfo(): void
    {
        $this->gateway->method('registerSepaPayment')->willReturnCallback(function () {
            return new RegisterSepaPaymentResponse('tid', '', '');
        });
        $scaTransactionData = new ScaTransactionData('iban', 'John Doe', 100);
        $this->paymentMethod->createScaRedirectUrl($scaTransactionData);

        $this->assertIsString($scaTransactionData->scaRedirectUrl);
        $this->assertIsString($scaTransactionData->transactionId);
    }
}
