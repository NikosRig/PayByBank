<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use MongoDB\Collection;
use PayByBank\Domain\Entity\Jwt;
use PayByBank\Domain\Repository\JwtRepository;
use PayByBank\Domain\ValueObjects\JwtState;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoJwtRepository implements JwtRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('jwt');
    }

    public function findByToken(string $token): ?Jwt
    {
        if (!$jwt = $this->collection->findOne(['token' => $token])) {
            return null;
        }

        $jwtState = new JwtState(
            $jwt->token,
            $jwt->mid,
            DateTime::createFromFormat('Y-m-d H:i:s', $jwt->dateCreated),
            $jwt->isUsed
        );

        return Jwt::fromState($jwtState);
    }

    public function save(Jwt $jwt): void
    {
        $this->collection->insertOne(
            $jwt->getState()->toArray()
        );
    }
}
