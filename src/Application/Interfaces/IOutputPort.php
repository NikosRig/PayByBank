<?php

declare(strict_types=1);

namespace PayByBank\Application\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface IOutputPort
{
    public function __invoke(ServerRequestInterface $request): string;
}