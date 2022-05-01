<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\GetPaymentOrderAuth;

use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthPresenter;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthRequest;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPaymentOrderAuthAction implements Action
{
    private readonly GetPaymentOrderAuthUseCase $getPaymentOrderAuthUseCase;

    private readonly Template $template;

    public function __construct(
        GetPaymentOrderAuthUseCase $getPaymentOrderAuthUseCase,
        Template $template
    ) {
        $this->getPaymentOrderAuthUseCase = $getPaymentOrderAuthUseCase;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $paymentOrderToken = $serverRequest->getAttribute('token');

        $request = new GetPaymentOrderAuthRequest($paymentOrderToken);
        $presenter = new GetPaymentOrderAuthPresenter();
        $this->getPaymentOrderAuthUseCase->get($request, $presenter);
        $template = $this->template->render('bankAuth.html', [
            'bank' => $presenter->getBankName()
        ]);

        return HttpResponseFactory::create($template);
    }
}
