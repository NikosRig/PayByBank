<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class CreditorNameRule implements ValidationRule
{
    /**
     * @param array $params
     * @return void
     */
    public function check(array $params): void
    {
        isset($params['creditorName']) && is_string($params['creditorName'])
            || throw new InvalidArgumentException('Invalid creditorName');
    }
}
