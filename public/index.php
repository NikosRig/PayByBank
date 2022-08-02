<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use Larium\Framework\Framework;
use Larium\Framework\Middleware\ActionResolverMiddleware;
use Larium\Framework\Middleware\RoutingMiddleware;
use PayByBank\WebApi\Middleware\ExceptionMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$rootPath = __DIR__ . '/..';
$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions("{$rootPath}/config/ContainerConfig.php");
$framework = new Framework($containerBuilder->build());

$framework->pipe(RoutingMiddleware::class);
$framework->pipe(ActionResolverMiddleware::class);
$framework->pipe(ExceptionMiddleware::class, 1);

$framework->run(ServerRequest::fromGlobals());
