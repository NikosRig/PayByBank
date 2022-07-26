<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class TransactionIdRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['transactionId']) && is_string($params['transactionId'])
        || throw new InvalidArgumentException('Invalid transactionId.');
    }
}
