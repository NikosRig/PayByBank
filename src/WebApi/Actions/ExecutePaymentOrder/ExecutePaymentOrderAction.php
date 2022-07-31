<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\ExecutePaymentOrder;

use Larium\Bridge\Template\Template;
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

    private readonly Template $template;

    public function __construct(
        ExecutePaymentOrderUseCase $useCase,
        LoggerInterface $logger,
        ExecutePaymentOrderValidatorBuilder $validatorBuilder,
        Template $template
    ) {
        $this->useCase = $useCase;
        $this->logger = $logger;
        $this->validatorBuilder = $validatorBuilder;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $requestParams = $serverRequest->getQueryParams();
        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $request = new ExecutePaymentOrderRequest(
                $requestParams['state'],
                $requestParams['code'] ?? null,
            );
            $this->useCase->execute($request);
            $template = $this->template->render('payment_order_succeed.twig');
            return HttpResponseFactory::create($template);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(null, 500);
        }
    }
}
