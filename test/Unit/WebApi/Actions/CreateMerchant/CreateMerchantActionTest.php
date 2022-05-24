<?php

namespace Test\Unit\WebApi\Actions\CreateMerchant;

use Exception;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantUseCase;
use PayByBank\WebApi\Actions\CreateMerchant\CreateMerchantAction;
use PayByBank\WebApi\Actions\CreateMerchant\CreateMerchantValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateMerchantActionTest extends ActionTestCase
{
    public function testAssertBadRequestWhenUsernameValidationFailed(): void
    {
        $createMerchantUseCaseMock = $this->createMock(CreateMerchantUseCase::class);
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCaseMock, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['password' => 'password']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertBadRequestWhenPasswordValidationFailed(): void
    {
        $createMerchantUseCaseMock = $this->createMock(CreateMerchantUseCase::class);
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCaseMock, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['username' => 'username']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertBadRequestStatusWhenUseCaseThrowsException(): void
    {
        $createMerchantUseCaseMock = $this->createMock(CreateMerchantUseCase::class);
        $createMerchantUseCaseMock->expects($this->once())->method('create')
            ->willThrowException(new Exception('error'));
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCaseMock, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['username' => 'username', 'password' => 'password']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(null, $response->getBody()->getContents());
    }

    public function testAssertCreatedStatusWhenMerchantSuccessfullyCreated(): void
    {
        $createMerchantUseCaseMock = $this->createMock(CreateMerchantUseCase::class);
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCaseMock, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['username' => 'username', 'password' => 'password']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(null, $response->getBody()->getContents());
    }
}
