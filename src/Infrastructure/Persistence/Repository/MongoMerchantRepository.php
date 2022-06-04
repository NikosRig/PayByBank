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

    public function findByMid(string $mid): ?Merchant
    {
        if (!$merchant = $this->collection->findOne(['mid' => $mid])) {
            return null;
        }

        $merchantState = new MerchantState(
            $merchant->mid,
            $merchant->firstName,
            $merchant->lastName,
            DateTime::createFromFormat('Y-m-d H:i:s', $merchant->dateCreated),
            $merchant->_id->__toString()
        );

        return Merchant::fromState($merchantState);
    }

    public function save(Merchant $merchant): void
    {
        $this->collection->insertOne([
            'mid' => $merchant->getMid(),
            'firstName' => $merchant->getFirstName(),
            'lastName' => $merchant->getLastName(),
            'dateCreated' => $merchant->getDateCreated()->format('Y-m-d H:i:s')
        ]);
    }
}
