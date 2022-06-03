<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class MidRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['mid']) && is_string($params['mid'])
        || throw new InvalidArgumentException('Invalid mid.');
    }
}
