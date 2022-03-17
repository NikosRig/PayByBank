<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface IOutputPort
{
    public function __invoke(ServerRequestInterface $request): string;
}