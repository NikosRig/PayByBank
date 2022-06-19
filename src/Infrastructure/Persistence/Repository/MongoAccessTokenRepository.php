<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use MongoDB\Collection;
use PayByBank\Domain\Entity\AccessToken;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\ValueObjects\AccessTokenState;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoAccessTokenRepository implements AccessTokenRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('access_tokens');
    }

    public function findByToken(string $token): ?AccessToken
    {
        if (!$accessToken = $this->collection->findOne(['token' => $token])) {
            return null;
        }

        $state = new AccessTokenState(
            $accessToken->token,
            $accessToken->merchantId,
            DateTime::createFromFormat('Y-m-d H:i:s', $accessToken->dateCreated),
            DateTime::createFromFormat('Y-m-d H:i:s', $accessToken->expirationDate),
            $accessToken->isUsed
        );

        return AccessToken::fromState($state);
    }

    public function save(AccessToken $accessToken): void
    {
        $this->collection->insertOne([
            'token' => $accessToken->getToken(),
            'merchantId' => $accessToken->getMerchantId(),
            'dateCreated' => $accessToken->getDateCreated()->format('Y-m-d H:i:s'),
            'expirationDate' => $accessToken->getExpirationDate()->format('Y-m-d H:i:s'),
            'isUsed' => $accessToken->isUsed()
        ]);
    }
}
