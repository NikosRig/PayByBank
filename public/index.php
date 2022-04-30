<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use Larium\Framework\Framework;
use Larium\Framework\Middleware\ActionResolverMiddleware;
use Larium\Framework\Middleware\RoutingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$rootPath = __DIR__ . '/..';
$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions("{$rootPath}/config/definitions.php");
$framework = new Framework($containerBuilder->build());

$framework->pipe(RoutingMiddleware::class, 1);
$framework->pipe(ActionResolverMiddleware::class);

$framework->run(ServerRequest::fromGlobals());
