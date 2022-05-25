<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\MerchantName;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateMerchantValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new MerchantName());
    }
}
