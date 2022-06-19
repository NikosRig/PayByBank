<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions\CreatePaymentOrder;

use DateTime;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Entity\AccessToken;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreatePaymentOrderActionTest extends ActionTestCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    public function setUp(): void
    {
        $this->paymentOrderRepository = $this->createMock(PaymentOrderRepository::class);
        $this->accessTokenRepository = $this->createMock(AccessTokenRepository::class);
    }

    public function testAssertShouldReturnPaymentOrderToken(): void
    {
        $accessToken = new AccessToken('merchantId', 'token', new DateTime());
        $this->accessTokenRepository->method('findByToken')->willReturn($accessToken);
        $requestBody = json_encode(['amount' => 10]);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, new CreatePaymentOrderValidatorBuilder());
        $serverRequest = $this->mockServerRequest($requestBody, [
            'Authorization' => 'Bearer token'
        ]);
        $serverRequest->method('getHeader')->willReturn(['Bearer token']);

        $response = $action($serverRequest);
        $responseBody = json_decode($response->getBody()->getContents());

        $this->assertObjectHasAttribute('token', $responseBody);
    }

    public function testAssertAmountIsRequired(): void
    {
        $request = $this->mockServerRequest(json_encode([]));
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
    }

    public function testAssertStringAmountIsNotAccepted(): void
    {
        $requestBody = json_encode(['amount' => '10']);
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
    }

    public function testAssertErrorWithNonJsonBody(): void
    {
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);
        $response =  $action($this->mockServerRequest());

        $this->assertResponseIsJsonAndHasError($response);
    }

    public function testAssertAccessTokenIsRequired(): void
    {
        $requestBody = json_encode(['amount' => 10]);
        $request = $this->mockServerRequest($requestBody);
        $createPaymentOrderUseCase = new CreatePaymentOrderUseCase(
            $this->paymentOrderRepository,
            $this->accessTokenRepository
        );
        $validatorBuilder = new CreatePaymentOrderValidatorBuilder();
        $action = new CreatePaymentOrderAction($createPaymentOrderUseCase, $validatorBuilder);

        $this->assertResponseIsJsonAndHasError($action($request));
    }

    private function assertResponseIsJsonAndHasError(ResponseInterface $response): void
    {
        $responseBody = $response->getBody()->getContents();
        $this->assertJson($responseBody);
        $this->assertObjectHasAttribute('error', json_decode($responseBody));
    }
}
