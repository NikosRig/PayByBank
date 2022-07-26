<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\ExecutePaymentOrder;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\TransactionIdRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class ExecutePaymentOrderValidatorBuilder implements ValidatorBuilder
{
    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule(new TransactionIdRule());
    }
}
