<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class AmountRule implements ValidationRule
{
    /**
     * @param array $params
     * @return void
     */
    public function check(array $params): void
    {
        isset($params['amount']) && is_int($params['amount'])
        || throw new InvalidArgumentException('Invalid amount');
    }
}
