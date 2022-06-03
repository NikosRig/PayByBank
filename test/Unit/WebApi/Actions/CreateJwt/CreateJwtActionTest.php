<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreateJwt;

use Config\JwtConfig;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtUseCase;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\JwtRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\WebApi\Actions\CreateJwt\CreateJwtAction;
use PayByBank\WebApi\Actions\CreateJwt\CreateJwtValidatorBuilder;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateJwtActionTest extends ActionTestCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly JwtRepository $jwtRepository;

    private readonly JwtConfig $jwtConfig;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->jwtRepository = $this->createMock(JwtRepository::class);
        $this->jwtConfig = new JwtConfig('PayBybank', 'secret-key', 900);
    }

    public function testExpectBadRequestWhenMidIsMissing(): void
    {
        $createJwtUseCase = new CreateJwtUseCase(
            $this->merchantRepository,
            $this->jwtRepository
        );
        $createJwtAction = new CreateJwtAction(
            $createJwtUseCase,
            new CreateJwtValidatorBuilder(),
            $this->jwtConfig
        );
        $response = $createJwtAction($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertBadRequestStatusWhenUseCaseThrowsException(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(null);
        $createJwtUseCase = new CreateJwtUseCase(
            $this->merchantRepository,
            $this->jwtRepository
        );
        $createJwtAction = new CreateJwtAction(
            $createJwtUseCase,
            new CreateJwtValidatorBuilder(),
            $this->jwtConfig
        );
        $serverRequest = $this->mockServerRequest(json_encode(['mid' => 'mid']));
        $response = $createJwtAction($serverRequest);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnToken(): void
    {
        $this->merchantRepository->method('findByMid')->willReturn(
            new Merchant('mid', 'Nikos', 'Rigas')
        );

        $createJwtUseCase = new CreateJwtUseCase(
            $this->merchantRepository,
            $this->jwtRepository
        );
        $createJwtAction = new CreateJwtAction(
            $createJwtUseCase,
            new CreateJwtValidatorBuilder(),
            $this->jwtConfig
        );
        $serverRequest = $this->mockServerRequest(json_encode(['mid' => 'mid']));
        $response = $createJwtAction($serverRequest);
        $responseBody = json_decode($response->getBody()->getContents());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertObjectHasAttribute('token', $responseBody);
    }
}
