<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateMerchant;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\PasswordRule;
use PayByBank\WebApi\Modules\Validation\Rules\UsernameRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateMerchantValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new UsernameRule())
            ->withRule(new PasswordRule());
    }
}
