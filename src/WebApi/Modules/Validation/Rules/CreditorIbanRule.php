<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

use InvalidArgumentException;

class CreditorIbanRule implements ValidationRule
{
    /**
     * @param array $params
     * @return void
     */
    public function check(array $params): void
    {
        isset($params['creditorIban']) && is_string($params['creditorIban'])
        || throw new InvalidArgumentException('Invalid creditor iban.');
    }
}
