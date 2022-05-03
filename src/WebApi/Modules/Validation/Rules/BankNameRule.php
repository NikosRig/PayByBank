<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class BankNameRule implements ValidationRule
{
    public function check(array $params): void
    {
        $supportedBanks = ['ING'];

        isset($params['bank']) && in_array($params['bank'], $supportedBanks)
        || throw new InvalidArgumentException('Invalid bank');
    }
}
