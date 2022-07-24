<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class PsuIpRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['psuIp']) && is_string($params['psuIp'])
        && filter_var($params['psuIp'], FILTER_VALIDATE_IP)
        || throw new InvalidArgumentException('Invalid psu ip.');
    }
}
