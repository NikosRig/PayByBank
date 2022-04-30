<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Larium\Framework\Bridge\Routing\FastRouteBridge;
use Larium\Framework\Contract\Routing\Router;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoPaymentOrderRepository;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\GetPaymentOrderAuth\GetPaymentOrderAuthAction;

use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;

return [
    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    */
    PaymentOrderRepository::class => autowire(MongoPaymentOrderRepository::class),

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */
    Router::class => factory(function () {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routeCollector->post('/payment/order', CreatePaymentOrderAction::class);
            $routeCollector->get('/payment/order/auth/{token}', GetPaymentOrderAuthAction::class);
        });

        return new FastRouteBridge($dispatcher);
    })
];
