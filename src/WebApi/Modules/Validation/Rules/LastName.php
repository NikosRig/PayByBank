<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class LastName implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['lastName']) && is_string($params['lastName'])
        || throw new InvalidArgumentException('Invalid lastName.');
    }
}
