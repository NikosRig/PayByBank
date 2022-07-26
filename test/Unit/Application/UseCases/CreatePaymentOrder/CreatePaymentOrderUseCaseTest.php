<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCases\CreatePaymentOrder;

use DateTime;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderRequest;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Entity\AccessToken;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PHPUnit\Framework\TestCase;

class CreatePaymentOrderUseCaseTest extends TestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->accessTokenRepository = $this->createMock(AccessTokenRepository::class);
    }

    public function testShouldSavePaymentOrder(): void
    {
        $this->paymentOrderRepository->expects($this->once())->method('save');
        $this->accessTokenRepository->method('findByToken')->willReturnCallback(function () {
            return new AccessToken(
                'mid',
                'access_token',
                new DateTime()
            );
        });
        $useCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );

        $request = new CreatePaymentOrderRequest(10, 'access_token', 'description');
        $presenter = new CreatePaymentOrderPresenter();
        $useCase->create($request, $presenter);
    }

    public function testAssertPresenterHasPaymentOrderToken(): void
    {
        $this->accessTokenRepository->method('findByToken')->willReturnCallback(function () {
            return new AccessToken(
                'mid',
                'access_token',
                new DateTime()
            );
        });
        $useCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $request = new CreatePaymentOrderRequest(10, 'access_token', 'description');
        $presenter = new CreatePaymentOrderPresenter();
        $useCase->create($request, $presenter);

        $this->assertIsString($presenter->getPaymentOrderToken());
    }

    public function testExpectExceptionWhenAccessTokenCannotBeFound(): void
    {
        $useCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $request = new CreatePaymentOrderRequest(10, 'access_token', 'description');
        $presenter = new CreatePaymentOrderPresenter();
        $this->expectException(InvalidArgumentException::class);

        $useCase->create($request, $presenter);
    }

    public function testExpectExceptionWhenAccessTokenIsUsed(): void
    {
        $this->accessTokenRepository->method('findByToken')->willReturnCallback(function () {
            $accessToken = new AccessToken(
                'mid',
                'access_token',
                new DateTime()
            );
            $accessToken->markUsed();

            return $accessToken;
        });

        $useCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $request = new CreatePaymentOrderRequest(10, 'access_token', 'description');
        $presenter = new CreatePaymentOrderPresenter();
        $this->expectException(InvalidArgumentException::class);

        $useCase->create($request, $presenter);
    }

    public function testExpectAccessTokenShouldBeMarkedAsUsed(): void
    {
        $this->accessTokenRepository->method('findByToken')->willReturnCallback(function () {
            return new AccessToken(
                'mid',
                'access_token',
                new DateTime()
            );
        });

        $this->accessTokenRepository->method('save')->willReturnCallback(
            function (AccessToken $accessToken) {
                $this->assertTrue($accessToken->isUsed());
            }
        );

        $useCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $request = new CreatePaymentOrderRequest(10, 'access_token', 'description');
        $presenter = new CreatePaymentOrderPresenter();

        $useCase->create($request, $presenter);
    }
}
