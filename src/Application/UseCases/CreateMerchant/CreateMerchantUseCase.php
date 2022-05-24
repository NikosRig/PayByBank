<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateMerchant;

use Exception;
use PayByBank\Domain\Entity\Merchant;
use PayByBank\Domain\Repository\MerchantRepository;

class CreateMerchantUseCase
{
    private readonly MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * @throws Exception
     */
    public function create(CreateMerchantRequest $request): void
    {
        if ($this->merchantRepository->findByUsername($request->username)) {
            throw new Exception('Merchant already exists.');
        }
        $merchant = new Merchant($request->username, $request->password);
        $this->merchantRepository->save($merchant);
    }
}
