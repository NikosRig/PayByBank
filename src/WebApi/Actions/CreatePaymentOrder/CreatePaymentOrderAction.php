<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreatePaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderPresenter;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderRequest;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\WebApi\Actions\Action;
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

    public function __invoke(ServerRequestInterface $request): string
    {
        $requestBody = $request->getBody()->getContents();
        $requestParams = json_decode($requestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
        } catch (InvalidArgumentException $exception) {
            return json_encode(['error' => $exception->getMessage()]);
        }

        $request = new CreatePaymentOrderRequest(
            $requestParams['creditorIban'],
            $requestParams['creditorName'],
            $requestParams['amount'],
            $requestParams['bank']
        );
        $presenter = new CreatePaymentOrderPresenter();
        $this->createPaymentOrderUseCase->create($request, $presenter);

        return json_encode(['redirectUri' => "/{$presenter->getPaymentOrderToken()}"]);
    }
}
