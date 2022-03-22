<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

interface ValidationRule
{
    /**
     * @param array $params
     * @throws InvalidArgumentException
     * @return void
     */
    public function check(array $params): void;
}
