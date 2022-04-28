<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use PayByBank\WebApi\WebApp;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$webApp = new WebApp(Bridge::create());

$webApp->run();
