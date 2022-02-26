<?php

declare(strict_types=1);

namespace PayByBank\Domain\Http\Exceptions;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Throwable;

class BadResponseException extends Exception implements ClientExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}