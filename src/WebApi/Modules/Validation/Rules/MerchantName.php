<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class MerchantName implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['merchantName']) && is_string($params['merchantName'])
        || throw new InvalidArgumentException('Invalid merchantName.');
    }
}
