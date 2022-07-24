<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class BankCodeRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['bankCode']) && is_string($params['bankCode'])
        || throw new InvalidArgumentException('Invalid bank code.');
    }
}
