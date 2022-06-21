<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class FirstNameRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['firstName']) && is_string($params['firstName'])
        || throw new InvalidArgumentException('Invalid firstName.');
    }
}
