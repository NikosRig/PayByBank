<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use PayByBank\Domain\Entity\Merchant;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoMerchantRepository;
use PHPUnit\Framework\TestCase;

class MongoMerchantRepositoryTest extends TestCase
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

    public function testAssertMerchantShouldBeSaved(): void
    {
        $mid = bin2hex(openssl_random_pseudo_bytes(24));
        $merchantRepository = new MongoMerchantRepository(self::$mongoAdapter);
        $merchantRepository->save(new Merchant($mid));
        $merchant = $merchantRepository->findByMid($mid);

        $this->assertInstanceOf(Merchant::class, $merchant);
    }

    public function testAssertSavedMerchantHasCorrectValues(): void
    {
        $mid = bin2hex(openssl_random_pseudo_bytes(24));
        $merchantRepository = new MongoMerchantRepository(self::$mongoAdapter);
        $merchantRepository->save(new Merchant($mid));
        $merchant = $merchantRepository->findByMid($mid);

        $this->assertEquals($mid, $merchant->getState()->mid);
    }
}
