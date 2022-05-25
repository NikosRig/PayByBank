<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

use Exception;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\MerchantRepository;

final class CreateMerchantUseCase
{
    private readonly MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * @throws Exception
     */
    public function create(CreateMerchantRequest $request, CreateMerchantPresenter $presenter): void
    {
        $mid = bin2hex(openssl_random_pseudo_bytes(24));
        if ($this->merchantRepository->findByMid($mid)) {
            throw new Exception('Merchant already exists.');
        }
        $merchant = new Merchant($mid, $request->merchantName);
        $this->merchantRepository->save($merchant);
        $presenter->present($mid);
    }
}
