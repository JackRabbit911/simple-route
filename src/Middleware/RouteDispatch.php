<?php declare(strict_types=1);

namespace Az\Route\Middleware;

use Az\Route\Resolver;
use Az\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatch implements MiddlewareInterface
{
    private Resolver $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$route = $request->getAttribute(Route::class)) {
            return $handler->handle($request);
        }

        $routeHandler = $route->getHandler();
        $instance = $this->resolver->resolve($routeHandler);
        $response = $instance->handle($request);

        return $response;
    }
}
