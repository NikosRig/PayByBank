<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreateBankAccount;

use DateTime;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PayByBank\WebApi\Actions\CreateBankAccount\CreateBankAccountAction;
use PayByBank\WebApi\Actions\CreateBankAccount\CreateBankAccountValidatorBuilder;
use PayByBank\WebApi\Modules\Validation\Rules\AccountHolderNameRule;
use PayByBank\WebApi\Modules\Validation\Rules\IbanRule;
use PayByBank\WebApi\Modules\Validation\Rules\MidRule;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateBankAccountActionTest extends ActionTestCase
{
    private readonly BankAccountRepository $bankAccountRepository;

    private readonly MerchantRepository $merchantRepository;

    private readonly CreateBankAccountUseCase $useCase;

    private readonly CreateBankAccountValidatorBuilder $validatorBuilder;

    public function setUp(): void
    {
        $this->bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->useCase = new CreateBankAccountUseCase(
            $this->merchantRepository,
            $this->bankAccountRepository
        );
        $this->validatorBuilder = new CreateBankAccountValidatorBuilder(
            new MidRule(),
            new AccountHolderNameRule(),
            new IbanRule()
        );
    }

    public function testExpectBadRequestWithInvalidMidParameter(): void
    {
        $action = new CreateBankAccountAction($this->useCase, $this->validatorBuilder);
        $requestBody = json_encode([
            'accountHolderName' => 'Nick Rigas',
            'iban' => 'NL49RABO1579872026'
        ]);
        $request = $this->mockServerRequest($requestBody);
        $response = $action($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWithInvalidIbanParameter(): void
    {
        $action = new CreateBankAccountAction($this->useCase, $this->validatorBuilder);
        $requestBody = json_encode([
            'mid' => 'mid',
            'accountHolderName' => 'Nick Rigas'
        ]);
        $request = $this->mockServerRequest($requestBody);
        $response = $action($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestWithInvalidAccountHolderNameParameter(): void
    {
        $action = new CreateBankAccountAction($this->useCase, $this->validatorBuilder);
        $requestBody = json_encode([
            'mid' => 'mid',
            'iban' => 'NL49RABO1579872026'
        ]);
        $request = $this->mockServerRequest($requestBody);
        $response = $action($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpectBadRequestUseCaseThrowsException(): void
    {
        $this->merchantRepository->method('findByMid')->willThrowException(
            new InvalidArgumentException()
        );
        $action = new CreateBankAccountAction($this->useCase, $this->validatorBuilder);
        $request = $this->mockServerRequest(json_encode(['mid' => 'mid']));
        $response = $action($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertResponseShouldHasCreatedStatus(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            $this->createMerchant()
        );
        $this->bankAccountRepository->method('findByBankCodeAndMerchantId')->willReturn(null);
        $action = new CreateBankAccountAction($this->useCase, $this->validatorBuilder);
        $requestBody = json_encode([
            'mid' => 'mid',
            'iban' => 'NL49RABO1579872026',
            'accountHolderName' => 'Nick Rigas'
        ]);
        $request = $this->mockServerRequest($requestBody);
        $response = $action($request);

        $this->assertEquals(201, $response->getStatusCode());
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
}
