<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class IbanRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['iban']) && is_string($params['iban'])
        || throw new InvalidArgumentException('Invalid iban.');
    }
}
