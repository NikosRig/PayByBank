<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class BankRule implements IValidationRule
{
    public function check(object $params): void
    {
        $supportedBanks = ['ING'];

        isset($params->bank) && in_array($params->bank, $supportedBanks)
        || throw new InvalidArgumentException('Invalid bank');
    }
}
