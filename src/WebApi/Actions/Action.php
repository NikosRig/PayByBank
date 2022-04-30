<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Action
{
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface;
}