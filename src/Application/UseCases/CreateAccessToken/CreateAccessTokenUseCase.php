<?php

declare(strict_types=1);

namespace PayByBank\Application\UseCases\CreateAccessToken;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use PayByBank\Domain\Entity\AccessToken;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\MerchantRepository;

final class CreateAccessTokenUseCase
{
    private readonly MerchantRepository $merchantRepository;

    private readonly AccessTokenRepository $accessTokenRepository;

    public function __construct(MerchantRepository $merchantRepository, AccessTokenRepository $accessTokenRepository)
    {
        $this->merchantRepository = $merchantRepository;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @throws Exception
     */
    public function create(CreateAccessTokenRequest $request, CreateAccessTokenPresenter $presenter): void
    {
        if (!$merchant = $this->merchantRepository->findByMid($request->mid)) {
            throw new Exception('Merchant cannot be found.');
        }

        $now = strtotime("now");
        $expirationTime = $now + $request->tokenLifeTimeSeconds;
        $expirationDateTime = date('Y-m-d H:i:s', $expirationTime);
        $jwtPayload = [
            'iss' => $request->jwtIssuer,
            'aud' => $merchant->getLastName(),
            'iat' => $now,
            "nbf" => $now,
            "exp" => $expirationTime,
            "mid" => $merchant->getMid()
        ];

        $jwtToken = JWT::encode($jwtPayload, $request->jwtSecretKey, 'HS256');
        $accessToken = new AccessToken($merchant->getMid(), $jwtToken, new DateTime($expirationDateTime));
        $this->accessTokenRepository->save($accessToken);
        $presenter->present($jwtToken);
    }
}
