<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreateAccessToken;

use Config\AccessTokenConfig;
use DateTime;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\ValueObjects\MerchantState;
use PayByBank\WebApi\Actions\CreateAccessToken\CreateAccessTokenAction;
use PayByBank\WebApi\Actions\CreateAccessToken\CreateAccessTokenValidatorBuilder;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateAccessActionTest extends ActionTestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    private readonly AccessTokenConfig $accessTokenConfig;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $this->accessTokenConfig = new AccessTokenConfig('PayByBank', 'secret-key', 900);
    }

    public function testExpectBadRequestWhenMidIsMissing(): void
    {
        $useCase = new CreateAccessTokenUseCase(
            $this->merchantRepository,
            $this->accessTokenRepository
        );
        $action = new CreateAccessTokenAction(
            $useCase,
            new CreateAccessTokenValidatorBuilder(),
            $this->accessTokenConfig
        );
        $response = $action($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertBadRequestStatusWhenUseCaseThrowsException(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(null);
        $useCase = new CreateAccessTokenUseCase(
            $this->merchantRepository,
            $this->accessTokenRepository
        );
        $action = new CreateAccessTokenAction(
            $useCase,
            new CreateAccessTokenValidatorBuilder(),
            $this->accessTokenConfig
        );
        $serverRequest = $this->mockServerRequest(json_encode(['mid' => 'mid']));
        $response = $action($serverRequest);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnToken(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn($this->createMerchant());
        $useCase = new CreateAccessTokenUseCase(
            $this->merchantRepository,
            $this->accessTokenRepository
        );
        $action = new CreateAccessTokenAction(
            $useCase,
            new CreateAccessTokenValidatorBuilder(),
            $this->accessTokenConfig
        );
        $serverRequest = $this->mockServerRequest(json_encode(['mid' => 'mid']));
        $response = $action($serverRequest);
        $responseBody = json_decode($response->getBody()->getContents());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertObjectHasAttribute('token', $responseBody);
    }

    private function createMerchant(): Merchant
    {
        $state = new MerchantState('mid', 'Nick', 'Rigas', new DateTime(), 'id');
        return Merchant::fromState($state);
    }
}
