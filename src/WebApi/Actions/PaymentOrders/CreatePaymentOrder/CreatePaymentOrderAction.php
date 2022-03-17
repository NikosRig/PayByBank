<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\PaymentOrders\CreatePaymentOrder;

use Exception;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\IPaymentOrderStoreRepository;
use PayByBank\WebApi\Interfaces\IOutputPort;
use PayByBank\WebApi\Modules\RequestValidator;
use Psr\Http\Message\RequestInterface;

class CreatePaymentOrderAction implements IOutputPort
{
    private RequestValidator $requestValidator;

    private IPaymentOrderStoreRepository $paymentOrderStoreRepository;

    public function __construct(
        RequestValidator $requestValidator,
        IPaymentOrderStoreRepository $paymentOrderStoreRepository
    ) {
        $this->requestValidator = $requestValidator;
        $this->paymentOrderStoreRepository = $paymentOrderStoreRepository;
    }

    public function __invoke(RequestInterface $request): string
    {
        $requestBody = $request->getBody()->getContents();

        $validationRules = [
            'creditorIban' => 'required|min:6',
            'creditorName' => 'required|min:4',
            'amount' => 'required|numeric'
        ];

        try {
            $this->requestValidator->validateBody($requestBody, $validationRules);
            $requestParams = json_decode($requestBody);
            $createPaymentOrder = new CreatePaymentOrderUseCase($this->paymentOrderStoreRepository);

            $paymentOrderToken = $createPaymentOrder(
                $requestParams->creditorIban,
                $requestParams->creditorName,
                $requestParams->amount
            );

            return json_encode(['redirectUri' => "/{$paymentOrderToken}"]);
        } catch (Exception $exception) {
            return json_encode(['error' => $exception->getMessage()]);
        }
    }
}
