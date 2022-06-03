<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateJwt;

use InvalidArgumentException;
use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\MidRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateJwtValidatorBuilder implements ValidatorBuilder
{
    /**
     * @throws InvalidArgumentException
     */
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new MidRule());
    }
}
