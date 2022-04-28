<?php

declare(strict_types=1);

use PayByBank\Domain\Repository\PaymentOrderRepository;
use PayByBank\Infrastructure\Persistence\Repository\MongoPaymentOrderRepository;
use PayByBank\WebApi\Actions\CreatePaymentOrder\CreatePaymentOrderAction;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use function DI\autowire;
use function DI\factory;

final class WebApp
{
    private readonly ContainerInterface $container;

    private readonly App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
    }

    public function run(): void
    {
        $this->addEntries();
        $this->addRoutes();
        $this->app->run();
    }

    private function addEntries(): void
    {
        $this->container->set(ServerRequestInterface::class, factory(function () {
            return ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();
        }));
        $this->container->set(PaymentOrderRepository::class, autowire(MongoPaymentOrderRepository::class));
    }

    private function addRoutes(): void
    {
        $this->app->post('/payment/order', CreatePaymentOrderAction::class);
    }
}
