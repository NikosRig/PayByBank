<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\ExecutePaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\ExecutePaymentOrder\ExecutePaymentOrderRequest;
use PayByBank\Application\UseCases\ExecutePaymentOrder\ExecutePaymentOrderUseCase;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExecutePaymentOrderAction implements \PayByBank\WebApi\Actions\Action
{
    private readonly ExecutePaymentOrderUseCase $useCase;

    private readonly LoggerInterface $logger;

    private readonly ExecutePaymentOrderValidatorBuilder $validatorBuilder;

    public function __construct(
        ExecutePaymentOrderUseCase $useCase,
        LoggerInterface $logger,
        ExecutePaymentOrderValidatorBuilder $validatorBuilder
    ) {
        $this->useCase = $useCase;
        $this->logger = $logger;
        $this->validatorBuilder = $validatorBuilder;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];
        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $request = new ExecutePaymentOrderRequest(
                $requestParams['transactionId'],
                $requestParams['authCode'] ?? null,
            );
            $this->useCase->execute($request);
        } catch (InvalidArgumentException $e) {
            return HttpResponseFactory::createJson(['error' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(null, 500);
        }

        return HttpResponseFactory::create(null, 200);
    }
}
