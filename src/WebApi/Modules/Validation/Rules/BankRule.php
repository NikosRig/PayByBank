<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class BankRule implements ValidationRule
{
    /**
     * @param array $params
     * @return void
     */
    public function check(array $params): void
    {
        $supportedBanks = ['ING'];

        isset($params['bank']) && in_array($params['bank'], $supportedBanks)
        || throw new InvalidArgumentException('Invalid bank');
    }
}
