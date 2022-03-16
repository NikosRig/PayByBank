<?php

declare(strict_types=1);

namespace PayByBank\WebApi\UseCases\V1\PaymentOrders\CreatePaymentOrder;

use Exception;
use PayByBank\Application\Interfaces\IOutputPort;
use PayByBank\WebApi\Modules\RequestValidator;
use Psr\Http\Message\RequestInterface;

class PaymentOrdersCreationController implements IOutputPort
{
    private RequestValidator $requestValidator;

    public function __construct(RequestValidator $requestValidator)
    {
        $this->requestValidator = $requestValidator;
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
            $requestParams = json_decode($requestBody, true);

            return '';
        } catch (Exception $exception) {
            return json_encode(['error' => $exception->getMessage()]);
        }
    }
}
