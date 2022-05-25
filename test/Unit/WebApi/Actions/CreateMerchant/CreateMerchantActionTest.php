<?php

namespace Test\Unit\WebApi\Actions\CreateMerchant;

use Exception;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantUseCase;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\WebApi\Actions\CreateMerchant\CreateMerchantAction;
use PayByBank\WebApi\Actions\CreateMerchant\CreateMerchantValidatorBuilder;
use Test\Unit\WebApi\Actions\ActionTestCase;

class CreateMerchantActionTest extends ActionTestCase
{
    public function testAssertBadRequestWhenMerchantNameValidationFails(): void
    {
        $createMerchantUseCase = new CreateMerchantUseCase(
            $this->createMock(MerchantRepository::class)
        );
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCase, new CreateMerchantValidatorBuilder());
        $response = $createMerchantAction($this->mockServerRequest());

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertBadRequestStatusWhenUseCaseThrowsException(): void
    {
        $merchantRepository =  $this->createMock(MerchantRepository::class);
        $merchantRepository->method('findByMid')->willThrowException(new Exception(''));
        $createMerchantUseCase = new CreateMerchantUseCase($merchantRepository);
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCase, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['merchantName' => 'Nick Rigas']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAssertWillReturnMidWhenMerchantIsCreated(): void
    {
        $createMerchantUseCase = new CreateMerchantUseCase(
            $this->createMock(MerchantRepository::class)
        );
        $createMerchantAction = new CreateMerchantAction($createMerchantUseCase, new CreateMerchantValidatorBuilder());
        $requestBody = json_encode(['merchantName' => 'Nick Rigas']);
        $response = $createMerchantAction($this->mockServerRequest($requestBody));
        $responsePayload =  json_decode($response->getBody()->getContents());

        $this->assertIsString($responsePayload->mid);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
