<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\AddAccount;

use DateTime;
use InvalidArgumentException;
use PayByBank\Application\UseCases\AddAccount\AddAccountRequest;
use PayByBank\Application\UseCases\AddAccount\AddAccountUseCase;
use PayByBank\Domain\Entity\Account;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\AccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PHPUnit\Framework\TestCase;

class AddAccountUseCaseTest extends TestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccountRepository $accountRepository;

    private readonly AddAccountUseCase $useCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->useCase = new AddAccountUseCase(
            $this->merchantRepository,
            $this->accountRepository
        );
    }

    public function testExpectExceptionWithInvalidMid(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(null);
        $request = $this->createAddAccountRequest();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->add($request);
    }

    public function testExpectItWontSaveAccountWithTheSameBankCode(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            $this->createMerchant()
        );
        $this->accountRepository->method('findByBankCodeAndMerchantId')->willReturn(
            $this->createMock(Account::class)
        );
        $this->accountRepository->expects($this->never())->method('save');
        $request = $this->createAddAccountRequest();

        $this->expectException(InvalidArgumentException::class);
        $this->useCase->add($request);
    }

    public function testAssertItWillCreateAccount(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            $this->createMerchant()
        );
        $this->accountRepository->method('findByBankCodeAndMerchantId')->willReturn(null);
        $this->accountRepository->expects($this->once())->method('save')->willReturnCallback(
            function (Account $account) {
                $this->assertIsString($account->getIban());
                $this->assertIsString($account->getMerchantId());
                $this->assertIsString($account->getAccountHolderName());
            }
        );
        $request = $this->createAddAccountRequest();
        $this->useCase->add($request);
    }

    private function createMerchant(): Merchant
    {
        $merchantState = new MerchantState(
            'mid',
            'Nick',
            'Rigas',
            new DateTime('now'),
            'merchantId'
        );
        return Merchant::fromState($merchantState);
    }

    private function createAddAccountRequest(): AddAccountRequest
    {
        return new AddAccountRequest(
            'NL53RABO1964258413',
            'Nick Rigas',
            'RABO',
            'mid'
        );
    }
}
