<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreatePaymentOrder;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\AmountRule;
use PayByBank\WebApi\Modules\Validation\Rules\BankRule;
use PayByBank\WebApi\Modules\Validation\Rules\CreditorIbanRule;
use PayByBank\WebApi\Modules\Validation\Rules\CreditorNameRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreatePaymentOrderValidatorBuilder implements ValidatorBuilder
{
    /**
     * @return Validator
     */
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new CreditorIbanRule())
            ->withRule(new CreditorNameRule())
            ->withRule(new AmountRule())
            ->withRule(new BankRule());
    }
}
