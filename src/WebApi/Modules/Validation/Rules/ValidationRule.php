<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

interface ValidationRule
{
    /**
     * @throws InvalidArgumentException
     */
    public function check(array $params): void;
}
