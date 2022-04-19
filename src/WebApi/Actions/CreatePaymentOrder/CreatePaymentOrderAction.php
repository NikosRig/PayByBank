<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreatePaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderInput;
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

        $input = new CreatePaymentOrderInput(
            $requestParams['creditorIban'],
            $requestParams['creditorName'],
            $requestParams['amount'],
            $requestParams['bank']
        );

        $output = $this->createPaymentOrderUseCase->create($input);

        return json_encode(['redirectUri' => "/{$output->paymentOrderToken}"]);
    }
}
