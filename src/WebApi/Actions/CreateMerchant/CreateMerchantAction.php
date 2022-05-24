<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantRequest;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateMerchantAction implements Action
{
    private readonly CreateMerchantUseCase $createMerchantUseCase;

    private readonly CreateMerchantValidatorBuilder $createMerchantValidatorBuilder;

    public function __construct(CreateMerchantUseCase $createMerchantUseCase, CreateMerchantValidatorBuilder $createMerchantValidatorBuilder)
    {
        $this->createMerchantUseCase = $createMerchantUseCase;
        $this->createMerchantValidatorBuilder = $createMerchantValidatorBuilder;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->createMerchantValidatorBuilder->build()->validate($requestParams);
        } catch (InvalidArgumentException $e) {
            return HttpResponseFactory::createJson(['error' => $e->getMessage()], 400);
        }

        $createMerchantRequest = new CreateMerchantRequest(
            $requestParams['username'],
            $requestParams['password']
        );

        try {
            $this->createMerchantUseCase->create($createMerchantRequest);
        } catch (Exception $e) {
            return HttpResponseFactory::create(null, 400);
        }

        return HttpResponseFactory::create(null, 201);
    }
}
