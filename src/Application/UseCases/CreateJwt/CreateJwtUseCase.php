<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateJwt;

use Exception;
use Firebase\JWT\JWT as JwtTokenGenerator;
use PayByBank\Domain\Entity\Jwt;
use PayByBank\Domain\Repository\JwtRepository;
use PayByBank\Domain\Repository\MerchantRepository;

final class CreateJwtUseCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly JwtRepository $jwtRepository;

    public function __construct(MerchantRepository $merchantRepository, JwtRepository $jwtRepository)
    {
        $this->merchantRepository = $merchantRepository;
        $this->jwtRepository = $jwtRepository;
    }

    /**
     * @throws Exception
     */
    public function create(CreateJwtRequest $request, CreateJwtPresenter $presenter): void
    {
        if (!$merchant = $this->merchantRepository->findByMid($request->mid)) {
            throw new Exception('Merchant cannot be found.');
        }

        $merchantState = $merchant->getState();
        $payload = [
            'iss' => $merchantState->firstName,
            'aud' => $merchantState->lastName,
            'iat' => time()
        ];
        $token = JwtTokenGenerator::encode($payload, $merchantState->mid, 'HS256');
        $jwt = new Jwt($merchantState->mid, $token);
        $this->jwtRepository->save($jwt);
        $presenter->present($token);
    }
}
