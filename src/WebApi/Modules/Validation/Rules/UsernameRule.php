<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class UsernameRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['username']) && is_string($params['username'])
        || throw new InvalidArgumentException('Invalid username.');
    }
}
