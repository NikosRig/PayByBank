<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\GetPaymentOrderAuth;

use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthPresenter;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthRequest;
use PayByBank\Application\UseCases\GetPaymentOrderAuth\GetPaymentOrderAuthUseCase;
use PayByBank\WebApi\Actions\Action;
use Psr\Http\Message\ServerRequestInterface;

class GetPaymentOrderAuthAction implements Action
{
    private GetPaymentOrderAuthUseCase $getPaymentOrderAuthUseCase;

    public function __construct(GetPaymentOrderAuthUseCase $getPaymentOrderAuthUseCase)
    {
        $this->getPaymentOrderAuthUseCase = $getPaymentOrderAuthUseCase;
    }

    public function __invoke(ServerRequestInterface $request): string
    {
        $paymentOrderToken = $request->getUri()->getQuery();

        $request = new GetPaymentOrderAuthRequest($paymentOrderToken);
        $presenter = new GetPaymentOrderAuthPresenter();
        $this->getPaymentOrderAuthUseCase->get($request, $presenter);

        # @ToDO find and render bank auth view using presenter
        return '';
    }
}
