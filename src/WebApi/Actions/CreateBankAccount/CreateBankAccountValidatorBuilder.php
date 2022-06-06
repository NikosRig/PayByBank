<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions\CreateBankAccount;

use PayByBank\WebApi\Modules\Validation\RequestParamValidator;
use PayByBank\WebApi\Modules\Validation\Rules\AccountHolderNameRule;
use PayByBank\WebApi\Modules\Validation\Rules\IbanRule;
use PayByBank\WebApi\Modules\Validation\Rules\MidRule;
use PayByBank\WebApi\Modules\Validation\Validator;
use PayByBank\WebApi\Modules\Validation\ValidatorBuilder;

class CreateBankAccountValidatorBuilder implements ValidatorBuilder
{
    private readonly MidRule $midRule;

    private readonly AccountHolderNameRule $accountHolderNameRule;

    private readonly ibanRule $ibanRule;

    public function __construct(
        MidRule $midRule,
        AccountHolderNameRule $accountHolderNameRule,
        IbanRule $ibanRule
    ) {
        $this->midRule = $midRule;
        $this->accountHolderNameRule = $accountHolderNameRule;
        $this->ibanRule = $ibanRule;
    }

    public function build(): Validator
    {
        return (new RequestParamValidator())
            ->withRule($this->midRule)
            ->withRule($this->ibanRule)
            ->withRule($this->accountHolderNameRule);
    }
}
