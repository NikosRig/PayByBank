<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class AccountHolderNameRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['accountHolderName']) && is_string($params['accountHolderName'])
            || throw new InvalidArgumentException('Invalid accountHolderName');
    }
}
