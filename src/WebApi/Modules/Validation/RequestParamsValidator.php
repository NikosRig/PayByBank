<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

use PayByBank\WebApi\Modules\Validation\Rules\IValidationRule;

class RequestParamsValidator
{
    private array $rules;

    public function validate(object $params): void
    {
        foreach ($this->rules as $rule) {
            $rule->check($params);
        }
    }

    public function withRule(IValidationRule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }
}
