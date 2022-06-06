<?php

declare(strict_types=1);

namespace Test\Integration\Infrastructure\Persistence\Repository;

use DateTime;
use PayByBank\Domain\Entity\AccessToken;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoAccessTokenRepository;
use PHPUnit\Framework\TestCase;

class MongoAccessTokenRepositoryTest extends TestCase
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

    public function testAssertAccessTokenShouldBeSaved(): void
    {
        $token = bin2hex(openssl_random_pseudo_bytes(24));
        $repository = new MongoAccessTokenRepository(self::$mongoAdapter);
        $repository->save(
            new AccessToken('mid', $token, new DateTime('now'))
        );
        $merchant = $repository->findByToken($token);

        $this->assertInstanceOf(AccessToken::class, $merchant);
    }
}
