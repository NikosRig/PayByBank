<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

use InvalidArgumentException;
use PayByBank\WebApi\Modules\Validation\Rules\ValidationRule;

interface Validator
{
    /**
     * @param array $params
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(array $params): void;

    /**
     * @param ValidationRule $rule
     * @return $this
     */
    public function withRule(ValidationRule $rule): self;
}
