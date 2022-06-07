<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoBankAccountRepository;
use Test\Integration\IntegrationTestCase;

class MongoBankAccountRepositoryTest extends IntegrationTestCase
{
    private static MongoAdapter $mongoAdapter;

    private readonly MongoBankAccountRepository $repository;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new MongoBankAccountRepository(self::$mongoAdapter);
    }

    public function testBankAccountShouldBeSaved(): void
    {
        $merchantId = $this->faker->name();
        $bankCode = $this->faker->name();

        $bankAccount = new BankAccount(
            $this->faker->iban(),
            $this->faker->name(),
            $merchantId,
            $bankCode,
        );
        $this->repository->save($bankAccount);

        $this->assertInstanceOf(
            BankAccount::class,
            $this->repository->findByBankCodeAndMerchantId($bankCode, $merchantId)
        );
    }

    public function testShouldReturnNullWhenBankAccountCannotBeFound(): void
    {
        $this->assertNull(
            $this->repository->findByBankCodeAndMerchantId('', '')
        );
    }

    public function testShouldReturnNullWhenMerchantHasNoAccounts(): void
    {
        $this->assertNull(
            $this->repository->findAllByMerchantId('')
        );
    }
}
