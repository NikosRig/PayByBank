<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class PaymentOrderTokenRule implements ValidationRule
{
    public function check(array $params): void
    {
        isset($params['paymentOrderToken']) && is_string($params['paymentOrderToken'])
        || throw new InvalidArgumentException('Invalid payment order token.');
    }
}
