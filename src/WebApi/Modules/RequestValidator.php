<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Modules;

use Exception;
use Rakit\Validation\Validator;

class RequestValidator
{
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function validateBody(string|null $requestBody, array $validationRules): void
    {
        $body = json_decode($requestBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('');
        }

        $validation = $this->validator->validate($body, $validationRules);

        if ($validation->fails()) {
            $firstError = $validation->errors()->firstOfAll()[0];
            throw new Exception($firstError);
        }
    }
}
