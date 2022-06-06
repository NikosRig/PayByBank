<?php

declare(strict_types=1);

use Config\AccessTokenConfig;
use FastRoute\RouteCollector;
use Larium\Bridge\Template\Template;
use Larium\Bridge\Template\TwigTemplate;
use Larium\Framework\Bridge\Routing\FastRouteBridge;
use Larium\Framework\Contract\Routing\Router;
use PayByBank\Domain\Repository\AccessTokenRepository;
use PayByBank\Domain\Repository\BankAccountRepository;
use PayByBank\Domain\Repository\MerchantRepository;
use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Domain\Repository\TransactionRepository;
use PayByBank\Infrastructure\Persistence\Adapters\MongoAdapter;
use PayByBank\Infrastructure\Persistence\Repository\MongoAccessTokenRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoBankAccountRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoMerchantRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoPaymentOrderRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoTransactionRepository;
use PayByBank\WebApi\Actions\CreateAccessToken\CreateAccessTokenAction;
use PayByBank\WebApi\Actions\CreateBankAccount\CreateBankAccountAction;
use PayByBank\WebApi\Actions\CreateMerchant\CreateMerchantAction;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use PayByBank\WebApi\Actions\GetPaymentOrderAuth\GetPaymentOrderAuthAction;

use function DI\autowire;
use function DI\create;
use function DI\env;
use function DI\factory;
use function FastRoute\simpleDispatcher;

return [
    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    */
    PaymentOrderRepository::class => autowire(MongoPaymentOrderRepository::class),
    TransactionRepository::class => autowire(MongoTransactionRepository::class),
    MerchantRepository::class => autowire(MongoMerchantRepository::class),
    AccessTokenRepository::class => autowire(MongoAccessTokenRepository::class),
    BankAccountRepository::class => autowire(MongoBankAccountRepository::class),

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */
    Router::class => factory(function () {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routeCollector->addGroup('/payment/order', function (RouteCollector $routeGroupCollector) {
                $routeGroupCollector->post('', CreatePaymentOrderAction::class);
                // $routeGroupCollector->get('/auth/{token}', GetPaymentOrderAuthAction::class);
            });

            $routeCollector->addGroup('/merchant', function (RouteCollector $routeGroupCollector) {
                $routeGroupCollector->post('', CreateMerchantAction::class);
                $routeGroupCollector->put('/accounts', CreateBankAccountAction::class);
            });

            $routeCollector->addGroup('/oauth2', function (RouteCollector $routeGroupCollector) {
                $routeGroupCollector->post('/token', CreateAccessTokenAction::class);
            });
        });

        return new FastRouteBridge($dispatcher);
    }),

    /*
    |--------------------------------------------------------------------------
    | Template
    |--------------------------------------------------------------------------
    */
    Template::class => create(TwigTemplate::class)->constructor(__DIR__ . '/../resources/templates'),

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */
    MongoAdapter::class => create(MongoAdapter::class)->constructor(
        env('DB'),
        env('DB_HOST'),
        env('DB_USER'),
        env('DB_USER_PASSWORD'),
        env('DB_PORT')
    ),

    /*
    |--------------------------------------------------------------------------
    | Config
    |--------------------------------------------------------------------------
    */
    AccessTokenConfig::class => create(AccessTokenConfig::class)->constructor(
        env('JWT_ISSUER'),
        env('JWT_SECRET_KEY'),
        (int) getenv('JWT_LIFETIME_SECONDS')
    ),
];
