<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateBankAccount;

use DateTime;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountRequest;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountUseCase;
use PayByBank\Domain\Entity\BankAccount;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PHPUnit\Framework\TestCase;

class CreateBankAccountUseCaseTest extends TestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly BankAccountRepository $bankAccountRepository;

    private readonly CreateBankAccountUseCase $useCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->useCase = new CreateBankAccountUseCase(
            $this->merchantRepository,
            $this->bankAccountRepository
        );
    }

    public function testExpectExceptionWithInvalidMid(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(null);
        $request = $this->createAddAccountRequest();
        $this->expectException(InvalidArgumentException::class);

        $this->useCase->create($request);
    }

    public function testExpectItWontSaveAccountWithTheSameBankCode(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            $this->createMerchant()
        );
        $this->bankAccountRepository->method('findByBankCodeAndMerchantId')->willReturn(
            $this->createMock(BankAccount::class)
        );
        $this->bankAccountRepository->expects($this->never())->method('save');
        $request = $this->createAddAccountRequest();

        $this->expectException(InvalidArgumentException::class);
        $this->useCase->create($request);
    }

    public function testAssertItWillCreateAccount(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            $this->createMerchant()
        );
        $this->bankAccountRepository->method('findByBankCodeAndMerchantId')->willReturn(null);
        $this->bankAccountRepository->expects($this->once())->method('save')->willReturnCallback(
            function (BankAccount $account) {
                $this->assertIsString($account->getIban());
                $this->assertIsString($account->getMerchantId());
                $this->assertIsString($account->getAccountHolderName());
            }
        );
        $request = $this->createAddAccountRequest();
        $this->useCase->create($request);
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

    private function createAddAccountRequest(): CreateBankAccountRequest
    {
        return new CreateBankAccountRequest(
            'NL53RABO1964258413',
            'Nick Rigas',
            'RABO',
            'mid'
        );
    }
}
