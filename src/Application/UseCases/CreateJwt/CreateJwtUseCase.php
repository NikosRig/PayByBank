<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateJwt;

use DateTime;
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

        $now = strtotime("now");
        $merchantState = $merchant->getState();
        $expirationTime = $now + $request->tokenLifeTimeSeconds;
        $expirationDateTime = date('Y-m-d H:i:s', $expirationTime);
        $jwtPayload = [
            'iss' => $request->jwtIssuer,
            'aud' => $merchantState->lastName,
            'iat' => $now,
            "nbf" => $now,
            "exp" => $expirationTime,
            "mid" => $merchantState->mid
        ];

        $jwtToken = JwtTokenGenerator::encode($jwtPayload, $request->jwtSecretKey, 'HS256');
        $jwt = new Jwt($merchantState->mid, $jwtToken, new DateTime($expirationDateTime));
        $this->jwtRepository->save($jwt);
        $presenter->present($jwtToken);
    }
}
