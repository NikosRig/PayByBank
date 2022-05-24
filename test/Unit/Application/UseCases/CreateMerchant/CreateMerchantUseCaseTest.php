<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateMerchant;

use Exception;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantRequest;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\MerchantRepository;
use PHPUnit\Framework\TestCase;

class CreateMerchantUseCaseTest extends TestCase
{
    private MerchantRepository $merchantRepository;

    private CreateMerchantUseCase $createMerchantUseCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->createMerchantUseCase = new CreateMerchantUseCase($this->merchantRepository);
    }

    public function testAssertExceptionWhenMerchantUsernameExists(): void
    {
        $username = 'username';
        $password = 'password';

        $this->merchantRepository->method('findByUsername')->willReturn(
            new Merchant($username, $password)
        );
        $createMerchantRequest = new CreateMerchantRequest($username, $password);

        $this->expectException(Exception::class);
        $this->createMerchantUseCase->create($createMerchantRequest);
    }

    /**
     * @throws Exception
     */
    public function testAssertSuccessfullyMerchantCreation(): void
    {
        $username = 'username';
        $password = 'password';

        $this->merchantRepository->method('findByUsername')->willReturn(null);
        $this->merchantRepository->expects($this->once())->method('save');
        $createMerchantRequest = new CreateMerchantRequest($username, $password);
        $this->createMerchantUseCase->create($createMerchantRequest);
    }
}
