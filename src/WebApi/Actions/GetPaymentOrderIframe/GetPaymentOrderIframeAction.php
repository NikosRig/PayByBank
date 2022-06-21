<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\GetPaymentOrderIframe;

use InvalidArgumentException;
use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsPresenter;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsRequest;
use PayByBank\Application\UseCases\GetPaymentOrderBankAccounts\GetPaymentOrderBankAccountsUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetPaymentOrderIframeAction implements Action
{
    private readonly GetPaymentOrderBankAccountsUseCase $useCase;

    private readonly Template $template;

    public function __construct(
        GetPaymentOrderBankAccountsUseCase $useCase,
        Template $template
    ) {
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

            $presenter = new GetPaymentOrderBankAccountsPresenter();
            $request = new GetPaymentOrderBankAccountsRequest(
                $paymentOrderToken
            );

            $this->useCase->get($request, $presenter);
            $template = $this->template->render(
                'iframe.html',
                $presenter->bankAccounts
            );

            return HttpResponseFactory::create($template);
        } catch (Throwable $e) {
            return HttpResponseFactory::create(null, 400);
        }
    }
}
