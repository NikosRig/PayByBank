<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use DateTime;
use MongoDB\Collection;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoMerchantRepository implements MerchantRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('merchants');
    }

    public function findByUsername(string $username): ?Merchant
    {
        if (!$merchant = $this->collection->findOne(['username' => $username])) {
            return null;
        }

        $merchantState = new MerchantState(
            $merchant->username,
            $merchant->password,
            DateTime::createFromFormat('Y-m-d H:i:s', $merchant->dateCreated)
        );

        return Merchant::fromState($merchantState);
    }

    public function save(Merchant $merchant): void
    {
        $this->collection->insertOne(
            $merchant->getState()->toArray()
        );
    }
}
