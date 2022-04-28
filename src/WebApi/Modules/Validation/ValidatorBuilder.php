<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

interface ValidatorBuilder
{
    public function build(): Validator;
}