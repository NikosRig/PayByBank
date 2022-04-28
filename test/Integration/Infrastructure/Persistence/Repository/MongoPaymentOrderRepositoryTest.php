<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use Dotenv\Dotenv;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PayByBank\Infrastructure\Persistence\Database\MongoDB;
use PayByBank\Infrastructure\Persistence\Repository\MongoPaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class MongoPaymentOrderRepositoryTest extends TestCase
{
    private static MongoDB $mongoDB;

    public static function setUpBeforeClass(): void
    {
        $baseDir = __DIR__ . '/../../../../../';
        $dotenv = Dotenv::createImmutable($baseDir);
        $dotenv->load();
        self::$mongoDB = new MongoDB();
    }

    public function testAssertPaymentOrderShouldBeSaved(): void
    {
        $creditorAccount = new CreditorAccount('NL47RABO6233671132', 'Nikos Rigas');
        $paymentOrder = new PaymentOrder($creditorAccount, 7000, 'ING');
        $repository = new MongoPaymentOrderRepository(self::$mongoDB);
        $repository->save($paymentOrder);
        $findResult = $repository->findByToken($paymentOrder->getToken());

        $this->assertEquals($paymentOrder->getToken(), $findResult->getToken());
    }
}
