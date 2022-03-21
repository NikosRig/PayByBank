<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\PaymentOrders\CreatePaymentOrder;

use InvalidArgumentException;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\WebApi\Actions\IAction;
use PayByBank\WebApi\Modules\Validation\RequestParamsValidator;
use PayByBank\WebApi\Modules\Validation\Rules\AmountRule;
use PayByBank\WebApi\Modules\Validation\Rules\BankRule;
use PayByBank\WebApi\Modules\Validation\Rules\CreditorIbanRule;
use PayByBank\WebApi\Modules\Validation\Rules\CreditorNameRule;
use Psr\Http\Message\RequestInterface;

class CreatePaymentOrderAction implements IAction
{
    private CreatePaymentOrderUseCase $createPaymentOrderUseCase;

    private RequestParamsValidator $requestParamsValidator;

    public function __construct(
        CreatePaymentOrderUseCase $createPaymentOrderUseCase,
        RequestParamsValidator $requestParamsValidator
    ) {
        $this->createPaymentOrderUseCase = $createPaymentOrderUseCase;
        $this->requestParamsValidator = $requestParamsValidator;
    }

    public function __invoke(RequestInterface $request): string
    {
        $requestBody = $request->getBody()->getContents();
        try {
            if (!$requestParams = json_decode($requestBody)) {
                throw new InvalidArgumentException('Invalid payload');
            }

            $this->requestParamsValidator
                ->withRule(new CreditorIbanRule())
                ->withRule(new CreditorNameRule())
                ->withRule(new AmountRule())
                ->withRule(new BankRule())
                ->validate($requestParams);

            $token = $this->createPaymentOrderUseCase->create(
                $requestParams->creditorIban,
                $requestParams->creditorName,
                $requestParams->amount,
                $requestParams->bank
            );

            return json_encode(['redirectUri' => "/{$token}"]);
        } catch (InvalidArgumentException $exception) {
            return json_encode(['error' => $exception->getMessage()]);
        }
    }
}
