<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\Exceptions;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;

class BadResponseException extends Exception implements ClientExceptionInterface
{
}
