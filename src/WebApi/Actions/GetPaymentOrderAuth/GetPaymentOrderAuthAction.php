<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\GetPaymentOrderAuth;

use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderPresenter;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderRequest;
use PayByBank\Application\UseCases\GetPaymentOrder\GetPaymentOrderUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPaymentOrderAuthAction implements Action
{
    private readonly GetPaymentOrderUseCase $getPaymentOrderAuthUseCase;

    private readonly Template $template;

    public function __construct(
        GetPaymentOrderUseCase $getPaymentOrderAuthUseCase,
        Template               $template
    ) {
        $this->getPaymentOrderAuthUseCase = $getPaymentOrderAuthUseCase;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $paymentOrderToken = $serverRequest->getAttribute('token');

        $request = new GetPaymentOrderRequest($paymentOrderToken);
        $presenter = new GetPaymentOrderPresenter();
        $this->getPaymentOrderAuthUseCase->get($request, $presenter);
        $template = $this->template->render('bankAuth.html', [
            'bank' => $presenter->getBankName()
        ]);

        return HttpResponseFactory::create($template);
    }
}
