<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\GetMerchantBankAccounts;

use Exception;
use PayByBank\Application\UseCases\GetMerchantBankAccounts\GetMerchantBankAccountsPresenter;
use PayByBank\Application\UseCases\GetMerchantBankAccounts\GetMerchantBankAccountsRequest;
use PayByBank\Application\UseCases\GetMerchantBankAccounts\GetMerchantBankAccountsUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Repository\BankAccountRepository;
use PHPUnit\Framework\TestCase;

class GetMerchantBankAccountsUseCaseTest extends TestCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly GetMerchantBankAccountsUseCase $useCase;

    public function setUp(): void
    {
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->useCase = new GetMerchantBankAccountsUseCase(
            $this->bankAccountRepository
        );
    }

    public function testExpectExceptionWhenMerchantHasNoBankAccounts(): void
    {
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturn(null);
        $request = new GetMerchantBankAccountsRequest('');
        $presenter = new GetMerchantBankAccountsPresenter();
        $this->expectException(Exception::class);

        $this->useCase->get($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testPresenterShouldHasBankAccount(): void
    {
        $this->bankAccountRepository->method('findAllByMerchantId')->willReturnCallback(function () {
            $bankAccounts = [];
            $bankAccounts[] = $this->createMock(BankAccount::class);

            return $bankAccounts;
        });
        $request = new GetMerchantBankAccountsRequest('');
        $presenter = new GetMerchantBankAccountsPresenter();
        $this->useCase->get($request, $presenter);

        $this->assertNotEmpty($presenter->bankAccounts);
    }
}
