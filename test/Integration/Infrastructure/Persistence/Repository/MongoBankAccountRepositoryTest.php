<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\ValueObjects\BankAccountState;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoBankAccountRepository;
use Test\Integration\IntegrationTestCase;

class MongoBankAccountRepositoryTest extends IntegrationTestCase
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

    public function testBankAccountShouldBeSaved(): void
    {
        $repository = new MongoBankAccountRepository(self::$mongoAdapter);
        $merchantId = $this->faker->name();
        $bankCode = $this->faker->name();

        $bankAccount = new BankAccount(
            $this->faker->iban(),
            $this->faker->name(),
            $merchantId,
            $bankCode,
        );
        $repository->save($bankAccount);

        $this->assertInstanceOf(
            BankAccount::class,
            $repository->findByBankCodeAndMerchantId($bankCode, $merchantId)
        );
    }
}
