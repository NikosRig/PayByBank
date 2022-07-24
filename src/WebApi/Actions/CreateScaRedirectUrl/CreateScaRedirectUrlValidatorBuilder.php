<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateScaRedirectUrl;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\BankCodeRule;
use PayByBank\WebApi\Modules\Validation\Rules\PaymentOrderTokenRule;
use PayByBank\WebApi\Modules\Validation\Rules\PsuIpRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateScaRedirectUrlValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new BankCodeRule())
            ->withRule(new PsuIpRule())
            ->withRule(new PaymentOrderTokenRule());
    }
}
