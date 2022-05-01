<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Factory;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HttpResponseFactory
{
    public static function create(string $body, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $body);
    }

    public static function createJson(array $payload, int $status = 200, array $headers = []): ResponseInterface
    {
        return self::create(
            json_encode($payload),
            $status,
            $headers
        );
    }
}
