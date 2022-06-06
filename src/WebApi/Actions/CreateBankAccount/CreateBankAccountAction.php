<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateBankAccount;

use Exception;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountRequest;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateBankAccountAction implements Action
{
    private readonly CreateBankAccountUseCase $useCase;

    private readonly CreateBankAccountValidatorBuilder $validatorBuilder;

    public function __construct(CreateBankAccountUseCase $useCase, CreateBankAccountValidatorBuilder $validatorBuilder)
    {
        $this->useCase = $useCase;
        $this->validatorBuilder = $validatorBuilder;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $request = new CreateBankAccountRequest('', '', '', '');
            $this->useCase->create($request);
        } catch (Exception $e) {
            return HttpResponseFactory::create(
                json_encode(['error' => 'Account failed to be created.']),
                400
            );
        }

        return HttpResponseFactory::create(null, 201);
    }
}
