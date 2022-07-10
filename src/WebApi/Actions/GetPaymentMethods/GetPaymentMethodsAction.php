<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\GetPaymentMethods;

use InvalidArgumentException;
use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsPresenter;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsRequest;
use PayByBank\Application\UseCases\GetPaymentMethods\GetPaymentMethodsUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetPaymentMethodsAction implements Action
{
    private readonly GetPaymentMethodsUseCase $useCase;

    private readonly Template $template;

    public function __construct(GetPaymentMethodsUseCase $useCase, Template $template)
    {
        $this->useCase = $useCase;
        $this->template = $template;
    }


    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $paymentOrderToken = $serverRequest->getAttribute('token');
        try {
            if (!$paymentOrderToken || !is_string($paymentOrderToken)) {
                throw new InvalidArgumentException('Invalid payment order token.');
            }

            $presenter = new GetPaymentMethodsPresenter();
            $request = new GetPaymentMethodsRequest($paymentOrderToken);
            $this->useCase->get($request, $presenter);

            $template = $this->template->render('payment_methods.html', [
                'bankCodes' => $presenter->bankCodes
            ]);

            return HttpResponseFactory::create($template);
        } catch (Throwable $e) {
            return HttpResponseFactory::create(null, 400);
        }
    }
}
