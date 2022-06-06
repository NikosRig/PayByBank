<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateJwt;

use Exception;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtPresenter;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtRequest;
use PayByBank\Application\UseCases\CreateJwt\CreateAccessTokenUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PHPUnit\Framework\TestCase;

class CreateJwtUseCaseTest extends TestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccessTokenRepository $jwtRepository;

    private readonly CreateAccessTokenUseCase $createJwtUseCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->jwtRepository = $this->createMock(AccessTokenRepository::class);
        $this->createJwtUseCase = new CreateAccessTokenUseCase(
            $this->merchantRepository,
            $this->jwtRepository
        );
    }

    public function testAssertExceptionWhenMerchantCannotBeFound(): void
    {
        $this->merchantRepository->expects($this->once())
        ->method('findByMid')->willReturn(null);
        $createJwtPresenter = new CreateJwtPresenter();
        $this->expectException(Exception::class);

        $this->createJwtUseCase->create($this->createJwtRequest(), $createJwtPresenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertJwtShouldBeSaved(): void
    {
        $merchant = new Merchant('mid', 'Nick', 'Rigas');
        $this->merchantRepository->expects($this->once())
            ->method('findByMid')->willReturn($merchant);
        $this->jwtRepository->expects($this->once())->method('save');
        $createJwtPresenter = new CreateJwtPresenter();
        $this->createJwtUseCase->create($this->createJwtRequest(), $createJwtPresenter);
    }

    /**
     * @throws Exception
     */
    public function testShouldReturnJwt(): void
    {
        $merchant = new Merchant('mid', 'Nick', 'Rigas');
        $this->merchantRepository->expects($this->once())
            ->method('findByMid')->willReturn($merchant);
        $createJwtPresenter = new CreateJwtPresenter();
        $this->createJwtUseCase->create($this->createJwtRequest(), $createJwtPresenter);

        $this->assertIsString($createJwtPresenter->token);
    }

    private function createJwtRequest(): CreateJwtRequest
    {
        return new CreateJwtRequest(
            'mid',
            'testIssuer',
            'secretKey',
            900
        );
    }
}
