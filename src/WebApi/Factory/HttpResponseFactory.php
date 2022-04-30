<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Factory;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HttpResponseFactory
{
    public static function createJson(array $payload, int $status = 200, array $headers = []): ResponseInterface
    {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json'
        ]);

        return new Response($status, $headers, json_encode($payload));
    }
}
