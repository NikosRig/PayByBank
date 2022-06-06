<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateAccessToken;

use Config\AccessTokenConfig;
use Exception;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenPresenter;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenRequest;
use PayByBank\Application\UseCases\CreateAccessToken\CreateAccessTokenUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateAccessTokenAction implements Action
{
    private readonly CreateAccessTokenUseCase $useCase;

    private readonly CreateAccessTokenValidatorBuilder $validatorBuilder;

    private readonly AccessTokenConfig $accessTokenConfig;

    public function __construct(
        CreateAccessTokenUseCase          $useCase,
        CreateAccessTokenValidatorBuilder $createJwtValidatorBuilder,
        AccessTokenConfig                 $accessTokenConfig
    ) {
        $this->useCase = $useCase;
        $this->validatorBuilder = $createJwtValidatorBuilder;
        $this->accessTokenConfig = $accessTokenConfig;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $presenter = new CreateAccessTokenPresenter();
            $request = new CreateAccessTokenRequest(
                $requestParams['mid'],
                $this->accessTokenConfig->issuer,
                $this->accessTokenConfig->secretKey,
                $this->accessTokenConfig->tokenLifeTimeSeconds
            );
            $this->useCase->create($request, $presenter);
        } catch (Exception $e) {
            return HttpResponseFactory::create(
                json_encode(['error' => 'Token failed to be created.']),
                400
            );
        }
        $responseBody = json_encode(['token' => $presenter->token]);

        return HttpResponseFactory::create($responseBody, 201);
    }
}
