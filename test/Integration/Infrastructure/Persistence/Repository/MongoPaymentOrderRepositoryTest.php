<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoPaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class MongoPaymentOrderRepositoryTest extends TestCase
{
    private static MongoAdapter $mongoAdapter;

    public static function setUpBeforeClass(): void
    {
        self::$mongoAdapter = new MongoAdapter(
            $_ENV['DB'],
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_USER_PASSWORD'],
            $_ENV['DB_PORT']
        );
    }

    public function testAssertPaymentOrderShouldBeSaved(): void
    {
        $paymentOrder = new PaymentOrder(7000, 'merchantId', 'description');
        $repository = new MongoPaymentOrderRepository(self::$mongoAdapter);
        $repository->save($paymentOrder);
        $findResult = $repository->findByToken($paymentOrder->getToken());

        $this->assertEquals($paymentOrder->getToken(), $findResult->getToken());
    }
}
