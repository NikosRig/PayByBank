<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateMerchant;

use Exception;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantPresenter;
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
        $mid = 'mid_widjwi';
        $firstName = 'Rigas';
        $lastName = 'Rigas';

        $this->merchantRepository->method('findByMid')->willReturn(
            new Merchant($mid, $firstName, $lastName)
        );
        $request = new CreateMerchantRequest($firstName, $lastName);
        $presenter = new CreateMerchantPresenter();

        $this->expectException(Exception::class);
        $this->createMerchantUseCase->create($request, $presenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertSuccessfullyMerchantCreation(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(null);
        $this->merchantRepository->expects($this->once())->method('save');
        $request = new CreateMerchantRequest('Nick', 'Rigas');
        $presenter = new CreateMerchantPresenter();
        $this->createMerchantUseCase->create($request, $presenter);
    }
}
