<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreateAccessToken;

use DateTime;
use Exception;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenPresenter;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenRequest;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PHPUnit\Framework\TestCase;

class CreateAccessTokenUseCaseTest extends TestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    private readonly CreateAccessTokenUseCase $useCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $this->useCase = new CreateAccessTokenUseCase(
            $this->merchantRepository,
            $this->accessTokenRepository
        );
    }

    public function testAssertExceptionWhenMerchantCannotBeFound(): void
    {
        $this->merchantRepository->expects($this->once())
        ->method('findByMid')->willReturn(null);
        $presenter = new CreateAccessTokenPresenter();
        $this->expectException(Exception::class);

        $this->useCase->create($this->createJwtRequest(), $presenter);
    }

    /**
     * @throws Exception
     */
    public function testAssertJwtShouldBeSaved(): void
    {
        $this->merchantRepository->expects($this->once())
            ->method('findByMid')->willReturn($this->createMerchant());
        $this->accessTokenRepository->expects($this->once())->method('save');
        $presenter = new CreateAccessTokenPresenter();
        $this->useCase->create($this->createJwtRequest(), $presenter);
    }

    /**
     * @throws Exception
     */
    public function testShouldReturnJwt(): void
    {
        $this->merchantRepository->expects($this->once())
            ->method('findByMid')->willReturn($this->createMerchant());
        $presenter = new CreateAccessTokenPresenter();
        $this->useCase->create($this->createJwtRequest(), $presenter);

        $this->assertIsString($presenter->token);
    }

    private function createMerchant(): Merchant
    {
        $state = new MerchantState('mid', 'Nick', 'Rigas', new DateTime(), 'id');
        return Merchant::fromState($state);
    }

    private function createJwtRequest(): CreateAccessTokenRequest
    {
        return new CreateAccessTokenRequest(
            'mid',
            'testIssuer',
            'secretKey',
            900
        );
    }
}
