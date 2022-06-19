<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreatePaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderRequest;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreatePaymentOrderAction implements Action
{
    private CreatePaymentOrderUseCase $createPaymentOrderUseCase;

    private CreatePaymentOrderValidatorBuilder $validatorBuilder;

    public function __construct(
        CreatePaymentOrderUseCase $createPaymentOrderUseCase,
        CreatePaymentOrderValidatorBuilder $validatorBuilder
    ) {
        $this->createPaymentOrderUseCase = $createPaymentOrderUseCase;
        $this->validatorBuilder = $validatorBuilder;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $authHeader = $serverRequest->getHeader('Authorization') ?? null;
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];
        $requestParams['accessToken'] = !empty($authHeader) ? str_replace(['Bearer', ' '], '', $authHeader[0]) : null;

        try {
            $this->validatorBuilder->build()->validate($requestParams);
        } catch (InvalidArgumentException $e) {
            return HttpResponseFactory::createJson(['error' => $e->getMessage()]);
        }

        $request = new CreatePaymentOrderRequest($requestParams['amount'], $requestParams['accessToken']);
        $presenter = new CreatePaymentOrderPresenter();
        $this->createPaymentOrderUseCase->create($request, $presenter);

        return HttpResponseFactory::createJson(['token' => $presenter->getPaymentOrderToken()]);
    }
}
