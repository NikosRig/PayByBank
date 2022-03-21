<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Actions;

use Psr\Http\Message\ServerRequestInterface;

interface IAction
{
    public function __invoke(ServerRequestInterface $request): string;
}