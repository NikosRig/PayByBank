<?php

namespace Test\Unit\Infrastructure\Http\Gateway;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GatewayTestCase extends TestCase
{
    public function mockResponse(?string $body, int $status = 200): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($body);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }
}
