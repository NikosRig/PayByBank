<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Persistence\Repository;

use Dotenv\Dotenv;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\ValueObjects\CreditorAccount;
use PayByBank\Infrastructure\Persistence\Database\MongoDB;
use PayByBank\Infrastructure\Persistence\Repository\PaymentOrder\PaymentOrderStoreRepository;
use PHPUnit\Framework\TestCase;

class PaymentOrderStoreRepositoryTest extends TestCase
{
    private static MongoDB $mongoDb;

    public static function setUpBeforeClass(): void
    {
        $baseDir = __DIR__ . '/../../../../..';
        $dotenv = Dotenv::createImmutable($baseDir);
        $dotenv->load();
        self::$mongoDb = new MongoDB();
    }

    public function testAssertPaymentOrderShouldBeStored(): void
    {
        $creditorAccount = new CreditorAccount('NL47RABO6233671132', 'Nikos Rigas');
        $paymentOrder = new PaymentOrder($creditorAccount, 5000, 'bank');
        $storeRepository = new PaymentOrderStoreRepository(self::$mongoDb);
        $storeRepository->store($paymentOrder);

        $this->assertTrue(true);
    }
}
