<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreatePaymentOrder;

use InvalidArgumentException;
use PayByBank\Domain\Entity\PaymentOrder;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;

final class CreatePaymentOrderUseCase
{
    private readonly PaymentOrderRepository $paymentOrderRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    public function __construct(
        PaymentOrderRepository $paymentOrderRepository,
        AccessTokenRepository $accessTokenRepository
    ) {
        $this->paymentOrderRepository = $paymentOrderRepository;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function create(CreatePaymentOrderRequest $request, CreatePaymentOrderPresenter $presenter): void
    {
        $accessToken = $this->accessTokenRepository->findByToken(
            $request->accessToken
        );

        if (!$accessToken || $accessToken->isUsed()) {
            throw new InvalidArgumentException('Invalid access token');
        }

        $paymentOrder = new PaymentOrder(
            $request->amount,
            $accessToken->getMerchantId()
        );
        $accessToken->markUsed();
        $this->accessTokenRepository->save($accessToken);
        $this->paymentOrderRepository->save($paymentOrder);

        $presenter->present($paymentOrder->getToken());
    }
}
