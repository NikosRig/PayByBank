<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Http\PaymentMethods;

use Exception;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Entity\Transaction;
use PayByBank\Domain\ValueObjects\Psu;
use PayByBank\Infrastructure\Http\Gateway\ABNA\ABNAGateway;
use PayByBank\Infrastructure\Http\Gateway\ABNA\DTO\RegisterSepaPaymentResponse;
use PayByBank\Infrastructure\Http\Gateway\Exceptions\BadResponseException;
use PayByBank\Infrastructure\Http\PaymentMethods\ABNA;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

class ABNATest extends TestCase
{
    private readonly ABNA $paymentMethod;

    private readonly ABNAGateway $gateway;

    private readonly LoggerInterface $logger;

    public function setUp(): void
    {
        $this->gateway = $this->createMock(ABNAGateway::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->paymentMethod = new ABNA($this->gateway, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function testShouldHasScaInfo(): void
    {
        $this->gateway->method('registerSepaPayment')->willReturnCallback(function () {
            return new RegisterSepaPaymentResponse('', '', '');
        });
        $transaction = $this->createTransaction();
        $this->paymentMethod->createScaRedirectUrl($transaction);

        $this->assertTrue($transaction->hasScaInfo());
    }

    public function testShouldWriteLogsWhenGatewayThrowsException(): void
    {
        $this->gateway->method('registerSepaPayment')->willThrowException(
            new BadResponseException()
        );
        $transaction = $this->createTransaction();
        $this->logger->expects($this->once())->method('error');
        $this->expectException(Exception::class);

        $this->paymentMethod->createScaRedirectUrl($transaction);
    }

    private function createTransaction(): Transaction
    {
        $paymentOrder = new PaymentOrder(10, '1');
        $psu = new Psu('ip');
        $bankAccount = new BankAccount(
            'iban',
            'Nick',
            '1',
            'ABNA'
        );

        return new Transaction(
            $paymentOrder,
            $psu,
            $bankAccount
        );
    }
}
