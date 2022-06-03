<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

use InvalidArgumentException;

interface ValidatorBuilder
{
    /**
     * @throws InvalidArgumentException
     * @return Validator
     */
    public function build(): Validator;
}
