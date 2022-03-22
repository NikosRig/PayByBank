<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules\Validation;

use PayByBank\WebApi\Modules\Validation\Rules\ValidationRule;

class RequestParamValidator implements Validator
{
    /**
     * @var array
     */
    private array $rules;

    /**
     * @param array $params
     * @return void
     */
    public function validate(array $params): void
    {
        foreach ($this->rules as $rule) {
            $rule->check($params);
        }
    }

    /**
     * @param ValidationRule $rule
     * @return $this
     */
    public function withRule(ValidationRule $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}
