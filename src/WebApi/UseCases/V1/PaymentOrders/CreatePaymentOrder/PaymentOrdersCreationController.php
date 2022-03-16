<?php

declare(strict_types=1);

namespace PayByBank\WebApi\UseCases\V1\PaymentOrders\CreatePaymentOrder;

use Exception;
use PayByBank\Application\Interfaces\IOutputPort;
use PayByBank\Application\UseCases\CreatePaymentOrder\CreatePaymentOrderUseCase;
use PayByBank\Domain\Repository\IPaymentOrderPersistenceRepository;
use PayByBank\WebApi\Modules\RequestValidator;
use Psr\Http\Message\RequestInterface;

class PaymentOrdersCreationController implements IOutputPort
{
    private RequestValidator $requestValidator;

    private IPaymentOrderPersistenceRepository $orderPersistenceRepository;

    public function __construct(
        RequestValidator $requestValidator,
        IPaymentOrderPersistenceRepository $orderPersistenceRepository
    ) {
        $this->requestValidator = $requestValidator;
        $this->orderPersistenceRepository = $orderPersistenceRepository;
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
            $createPaymentOrder = new CreatePaymentOrderUseCase($this->orderPersistenceRepository);

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
