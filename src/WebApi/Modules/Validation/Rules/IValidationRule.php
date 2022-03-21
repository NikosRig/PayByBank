<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation\Rules;

interface IValidationRule
{
    public function check(object $params): void;
}
