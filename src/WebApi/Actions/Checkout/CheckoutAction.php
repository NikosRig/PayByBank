<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\Checkout;

use InvalidArgumentException;
use Larium\Bridge\Template\Template;
use PayByBank\Application\UseCases\Checkout\CheckoutPresenter;
use PayByBank\Application\UseCases\Checkout\CheckoutRequest;
use PayByBank\Application\UseCases\Checkout\CheckoutUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CheckoutAction implements Action
{
    private readonly CheckoutUseCase $useCase;

    private readonly Template $template;

    private readonly LoggerInterface $logger;

    public function __construct(
        CheckoutUseCase $useCase,
        Template $template,
        LoggerInterface $logger
    ) {
        $this->useCase = $useCase;
        $this->template = $template;
        $this->logger = $logger;
    }


    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $paymentOrderToken = $serverRequest->getAttribute('token');
        try {
            if (!$paymentOrderToken || !is_string($paymentOrderToken)) {
                throw new InvalidArgumentException('Invalid payment order token.');
            }

            $presenter = new CheckoutPresenter();
            $request = new CheckoutRequest($paymentOrderToken);
            $this->useCase->get($request, $presenter);

            $template = $this->template->render('payment_methods.html', [
                'bankCodes' => $presenter->bankCodes
            ]);

            return HttpResponseFactory::create($template);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(null, 400);
        }
    }
}
