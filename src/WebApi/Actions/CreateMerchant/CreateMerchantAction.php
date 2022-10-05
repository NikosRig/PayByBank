<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantPresenter;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantRequest;
use PayByBank\Application\UseCases\CreateMerchant\CreateMerchantUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateMerchantAction implements Action
{
    public function __construct(
        private readonly CreateMerchantUseCase $createMerchantUseCase,
        private readonly CreateMerchantValidatorBuilder $createMerchantValidatorBuilder,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->createMerchantValidatorBuilder->build()->validate($requestParams);
            $createMerchantRequest = new CreateMerchantRequest(
                $requestParams['firstName'],
                $requestParams['lastName']
            );
            $createMerchantPresenter = new CreateMerchantPresenter();
            $this->createMerchantUseCase->create($createMerchantRequest, $createMerchantPresenter);
            $responsePayload = json_encode(['mid' => $createMerchantPresenter->mid]);
            
            return HttpResponseFactory::create($responsePayload, 201);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(
                json_encode(['error' => 'Merchant failed to be created.']),
                400
            );
        }
    }
}
