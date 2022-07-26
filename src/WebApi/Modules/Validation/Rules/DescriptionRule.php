<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class DescriptionRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['description']) && is_string($params['description'])
        || throw new InvalidArgumentException('Invalid description.');
    }
}
