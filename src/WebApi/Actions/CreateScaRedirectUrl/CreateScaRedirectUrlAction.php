<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateScaRedirectUrl;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlPresenter;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlRequest;
use PayByBank\Application\UseCases\CreateScaRedirectUrl\CreateScaRedirectUrlUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateScaRedirectUrlAction implements Action
{
    private readonly CreateScaRedirectUrlUseCase $useCase;

    private readonly CreateScaRedirectUrlValidatorBuilder $validatorBuilder;

    private readonly LoggerInterface $logger;

    public function __construct(
        CreateScaRedirectUrlUseCase $useCase,
        CreateScaRedirectUrlValidatorBuilder $validatorBuilder,
        LoggerInterface $logger
    ) {
        $this->useCase = $useCase;
        $this->validatorBuilder = $validatorBuilder;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $presenter = new CreateScaRedirectUrlPresenter();
            $request = new CreateScaRedirectUrlRequest(
                $requestParams['paymentOrderToken'],
                $requestParams['bankCode'],
                $requestParams['psuIp'],
            );
            $this->useCase->create($request, $presenter);

            return HttpResponseFactory::createJson(['scaRedirectUrl' => $presenter->scaRedirectUrl], 201);
        } catch (InvalidArgumentException $e) {
            return HttpResponseFactory::createJson(['error' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(null, 500);
        }
    }
}
