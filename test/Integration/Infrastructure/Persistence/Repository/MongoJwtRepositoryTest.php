<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use DateTime;
use PayByBank\Domain\Entity\Jwt;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoJwtRepository;
use PHPUnit\Framework\TestCase;

class MongoJwtRepositoryTest extends TestCase
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

    public function testAssertJwtShouldBeSaved(): void
    {
        $token = bin2hex(openssl_random_pseudo_bytes(24));
        $jwtRepository = new MongoJwtRepository(self::$mongoAdapter);
        $jwtRepository->save(
            new Jwt('mid', $token, new DateTime('now'))
        );
        $merchant = $jwtRepository->findByToken($token);

        $this->assertInstanceOf(Jwt::class, $merchant);
    }
}
