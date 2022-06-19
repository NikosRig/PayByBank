<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class AccessTokenRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['accessToken']) && is_string($params['accessToken'])
        || throw new InvalidArgumentException('Access token is required.');
    }
}
