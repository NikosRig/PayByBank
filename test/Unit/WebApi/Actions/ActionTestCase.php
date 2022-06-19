<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class ActionTestCase extends TestCase
{
    public function mockServerRequest(string $body = '', array $headers = []): ServerRequestInterface
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->method('getHeaders')->willReturn($headers);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($body);
        $serverRequest->method('getBody')->willReturn($stream);

        return $serverRequest;
    }
}
