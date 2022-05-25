<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\FirstName;
use PayByBank\WebApi\Modules\Validation\Rules\LastName;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateMerchantValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new FirstName())
            ->withRule(new LastName());
    }
}
