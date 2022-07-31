<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class TransactionIdRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['state']) && is_string($params['state'])
        || throw new InvalidArgumentException('Transaction id seems to be invalid.');
    }
}
