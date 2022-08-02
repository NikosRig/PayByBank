<?php

declare(strict_types=1);

namespace PayByBank\WebApi\Middleware;

use Larium\Framework\Contract\Routing\HttpMethodNotAllowedException;
use Larium\Framework\Contract\Routing\HttpNotFoundException;
use PayByBank\WebApi\Factory\HttpResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionMiddleware implements MiddlewareInterface
{
    private readonly LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $e) {
            return HttpResponseFactory::create(null, 400);
        } catch (HttpMethodNotAllowedException $e) {
            return HttpResponseFactory::create(null, 405);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return HttpResponseFactory::create(null, 500);
        }
    }
}
