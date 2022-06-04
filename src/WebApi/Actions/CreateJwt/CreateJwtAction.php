<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateJwt;

use Config\JwtConfig;
use Exception;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtPresenter;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtRequest;
use PayByBank\Application\UseCases\CreateJwt\CreateJwtUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateJwtAction implements Action
{
    private readonly CreateJwtUseCase $createJwtUseCase;

    private readonly CreateJwtValidatorBuilder $validatorBuilder;

    private readonly JwtConfig $jwtConfig;

    public function __construct(
        CreateJwtUseCase $createJwtUseCase,
        CreateJwtValidatorBuilder $createJwtValidatorBuilder,
        JwtConfig $jwtConfig
    ) {
        $this->createJwtUseCase = $createJwtUseCase;
        $this->validatorBuilder = $createJwtValidatorBuilder;
        $this->jwtConfig = $jwtConfig;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $presenter = new CreateJwtPresenter();
            $request = new CreateJwtRequest(
                $requestParams['mid'],
                $this->jwtConfig->jwtIssuer,
                $this->jwtConfig->jwtSecretKey,
                $this->jwtConfig->tokenLifeTimeSeconds
            );
            $this->createJwtUseCase->create($request, $presenter);
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
