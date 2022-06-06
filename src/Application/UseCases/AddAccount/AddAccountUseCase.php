<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\AddAccount;

use InvalidArgumentException;
use PayByBank\Domain\Entity\Account;
use PayByBank\Domain\Repository\AccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;

final class AddAccountUseCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccountRepository $accountRepository;

    public function __construct(
        MerchantRepository $merchantRepository,
        AccountRepository $accountRepository
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->accountRepository = $accountRepository;
    }

    public function add(AddAccountRequest $request): void
    {
        if (!$merchant = $this->merchantRepository->findByMid($request->mid)) {
            throw new InvalidArgumentException("Mid {$request->mid} cannot be found.");
        }

        $bankCode = $request->bankCode;
        $merchantId = $merchant->getId();

        if ($this->accountRepository->findByBankCodeAndMerchantId($bankCode, $merchantId)) {
            throw new InvalidArgumentException("Duplicate account {$bankCode} {$merchantId}");
        }

        $account = new Account(
            $request->iban,
            $request->accountHolderName,
            $merchantId
        );
        $this->accountRepository->save($account);
    }
}
