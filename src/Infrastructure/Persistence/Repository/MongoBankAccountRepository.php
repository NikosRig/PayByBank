<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Repository;

use InvalidArgumentException;
use MongoDB\Collection;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\ValueObjects\BankAccountState;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;

class MongoBankAccountRepository implements BankAccountRepository
{
    private Collection $collection;

    public function __construct(MongoAdapter $mongoAdapter)
    {
        $this->collection = $mongoAdapter->selectCollection('bank_accounts');
    }

    public function findByBankCodeAndMerchantId(string $bankCode, string $merchantId): ?BankAccount
    {
        $bankAccount = $this->collection->findOne([
            'bankCode' => $bankCode,
            'merchantId' => $merchantId
        ]);

        if (!$bankAccount) {
            return null;
        }

        return $this->mapToBankAccount($bankAccount);
    }

    public function save(BankAccount $bankAccount): void
    {
        $this->collection->insertOne([
            'iban' => $bankAccount->getIban(),
            'bankCode' => $bankAccount->getBankCode(),
            'accountHolderName' => $bankAccount->getAccountHolderName(),
            'merchantId' => $bankAccount->getMerchantId()
        ]);
    }

    public function findAllByMerchantId(string $merchantId): ?array
    {
        $bankAccountsCollection = $this->collection->find(['merchantId' => $merchantId]);
        $bankAccounts = [];

        foreach ($bankAccountsCollection as $bankAccount) {
            $bankAccounts[] = $this->mapToBankAccount($bankAccount);
        }

        return !empty($bankAccounts) ? $bankAccounts : null;
    }

    public function findById(string $id): BankAccount
    {
        if (!$bankAccount = $this->collection->findOne(['_id' => $id])) {
            throw new InvalidArgumentException('Bank account cannot be found.');
        }

        return $this->mapToBankAccount($bankAccount);
    }

    private function mapToBankAccount(object $bankAccount): BankAccount
    {
        $state = new BankAccountState(
            $bankAccount->iban,
            $bankAccount->accountHolderName,
            $bankAccount->merchantId,
            $bankAccount->_id->__toString(),
            $bankAccount->bankCode
        );

        return BankAccount::fromState($state);
    }
}
