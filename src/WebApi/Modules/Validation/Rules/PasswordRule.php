<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class PasswordRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['password']) && is_string($params['password'])
        || throw new InvalidArgumentException('Invalid password.');
    }
}
