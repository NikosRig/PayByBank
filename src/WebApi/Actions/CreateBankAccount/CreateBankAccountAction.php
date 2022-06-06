<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateBankAccount;

use Exception;
use InvalidArgumentException;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountRequest;
use PayByBank\Application\UseCases\CreateBankAccount\CreateBankAccountUseCase;
use PayByBank\WebApi\Actions\Action;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use PHP_IBAN\IBAN;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class CreateBankAccountAction implements Action
{
    private readonly CreateBankAccountUseCase $useCase;

    private readonly CreateBankAccountValidatorBuilder $validatorBuilder;

    public function __construct(
        CreateBankAccountUseCase $useCase,
        CreateBankAccountValidatorBuilder $validatorBuilder
    ) {
        $this->useCase = $useCase;
        $this->validatorBuilder = $validatorBuilder;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $serverRequestBody = $serverRequest->getBody()->getContents();
        $requestParams = json_decode($serverRequestBody, true) ?? [];

        try {
            $this->validatorBuilder->build()->validate($requestParams);
            $iban = new IBAN($requestParams['iban']);

            if (!$iban->Verify()) {
                throw new InvalidArgumentException('Invalid iban.');
            }

            $request = new CreateBankAccountRequest(
                $requestParams['iban'],
                $requestParams['accountHolderName'],
                $iban->Bank(),
                $requestParams['mid']
            );
            $this->useCase->create($request);
        } catch (Throwable $e) {
            return HttpResponseFactory::create(
                json_encode(['error' => 'Account failed to be created']),
                400
            );
        }

        return HttpResponseFactory::create(null, 201);
    }
}
