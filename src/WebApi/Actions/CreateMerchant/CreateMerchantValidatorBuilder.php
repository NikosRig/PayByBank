<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\FirstNameRule;
use PayByBank\WebApi\Modules\Validation\Rules\LastNameRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateMerchantValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new FirstNameRule())
            ->withRule(new LastNameRule());
    }
}
