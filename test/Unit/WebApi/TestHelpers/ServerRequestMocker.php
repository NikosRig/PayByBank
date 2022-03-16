<?php

declare(strict_types=1);

namespace Test\Unit\WebApi\TestHelpers;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class ServerRequestMocker extends TestCase
{
    public static function mock(string $body = ''): ServerRequestInterface
    {
        $mocker = new ServerRequestMocker();
        $serverRequest = $mocker->createMock(ServerRequestInterface::class);
        $stream = $mocker->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($body);
        $serverRequest->method('getBody')->willReturn($stream);

        return $serverRequest;
    }
}
