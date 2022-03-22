<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

interface ValidatorBuilder
{
    /**
     * @return Validator
     */
    public function build(): Validator;
}