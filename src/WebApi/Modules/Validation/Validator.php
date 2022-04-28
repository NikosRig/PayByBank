<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

use InvalidArgumentException;
use PayByBank\WebApi\Modules\Validation\Rules\ValidationRule;

interface Validator
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(array $params): void;

    public function withRule(ValidationRule $rule): self;
}
