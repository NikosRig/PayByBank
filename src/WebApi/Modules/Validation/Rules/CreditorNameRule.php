<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class CreditorNameRule implements IValidationRule
{
    public function check(object $params): void
    {
        isset($params->creditorName) && is_string($params->creditorName)
            || throw new InvalidArgumentException('Invalid creditorName');
    }
}
