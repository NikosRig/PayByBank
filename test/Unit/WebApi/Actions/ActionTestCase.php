<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\Actions;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class ActionTestCase extends TestCase
{
    public function mockServerRequest(string $body = ''): ServerRequestInterface
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($body);
        $serverRequest->method('getBody')->willReturn($stream);

        return $serverRequest;
    }
}
