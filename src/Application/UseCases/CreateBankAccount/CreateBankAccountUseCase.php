<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateBankAccount;

use InvalidArgumentException;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;

final class CreateBankAccountUseCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly BankAccountRepository $accountRepository;

    public function __construct(
        MerchantRepository $merchantRepository,
        BankAccountRepository $accountRepository
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->accountRepository = $accountRepository;
    }

    public function create(CreateBankAccountRequest $request): void
    {
        if (!$merchant = $this->merchantRepository->findByMid($request->mid)) {
            throw new InvalidArgumentException("Mid {$request->mid} cannot be found.");
        }

        $bankCode = $request->bankCode;
        $merchantId = $merchant->getId();

        if ($this->accountRepository->findByBankCodeAndMerchantId($bankCode, $merchantId)) {
            throw new InvalidArgumentException("Duplicate account {$bankCode} {$merchantId}");
        }

        $bankAccount = new BankAccount(
            $request->iban,
            $request->accountHolderName,
            $merchantId
        );
        $this->accountRepository->save($bankAccount);
    }
}
