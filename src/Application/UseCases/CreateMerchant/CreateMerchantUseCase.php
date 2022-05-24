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
    public function create(CreateMerchantRequest $request): void
    {
        if ($this->merchantRepository->findByUsername($request->username)) {
            throw new Exception('Merchant already exists.');
        }
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        $merchant = new Merchant($request->username, $password);
        $this->merchantRepository->save($merchant);
    }
}
