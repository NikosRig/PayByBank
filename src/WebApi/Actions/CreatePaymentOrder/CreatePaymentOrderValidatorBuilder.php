<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreatePaymentOrder;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\AmountRule;
use PayByBank\WebApi\Modules\Validation\Rules\BankNameRule;
use PayByBank\WebApi\Modules\Validation\Rules\IbanRule;
use PayByBank\WebApi\Modules\Validation\Rules\AccountHolderNameRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreatePaymentOrderValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new IbanRule())
            ->withRule(new AccountHolderNameRule())
            ->withRule(new AmountRule())
            ->withRule(new BankNameRule());
    }
}
