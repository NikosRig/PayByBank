<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class AmountRule implements IValidationRule
{
    public function check(object $params): void
    {
        isset($params->amount) && is_int($params->amount)
        || throw new InvalidArgumentException('Invalid amount');
    }
}
