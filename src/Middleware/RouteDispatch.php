<?php declare(strict_types=1);

namespace Az\Route\Middleware;

use Az\Route\NormalizeResponse;
use Az\Route\Route;
use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Closure;

class RouteDispatch implements MiddlewareInterface
{
    use NormalizeResponse;

    private InvokerInterface $container;

    public function __construct(InvokerInterface $container)
    {
        $this->container = $container;
    }
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if (!$route = $request->getAttribute(Route::class)) {
            return $handler->handle($request);
        }

        $routeHandler = $route->getHandler();
        $params = array_merge(['request' => $request], $route->getParameters());

        if (!$routeHandler instanceof Closure) {
            if (is_a($routeHandler[0] ?? $routeHandler, MiddlewareInterface::class, true)) {
                $class = $routeHandler[0] ?? $routeHandler;
                $middleware = $this->container->make($class);
            } elseif (is_a($routeHandler[0] ?? $routeHandler, RequestHandlerInterface::class, true)) {
                $routeHandler[1] = 'handle';
            }
        }

        if (!isset($middleware)) {
            $middleware = $this->wrapper($this->container, $routeHandler, $params);
        }

        return $middleware->process($request, $handler);
    }

    private function wrapper($container, $routeHandler, $params): MiddlewareInterface
    {
        return new class ($container, $routeHandler, $params) implements MiddlewareInterface
        {
            use NormalizeResponse;
            
            private InvokerInterface $container;
            private $routeHandler;
            private $params;

            public function __construct(InvokerInterface $container, $routeHandler, $params)
            {
                $this->container = $container;
                $this->routeHandler = $routeHandler;
                $this->params = $params;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface
            {
                $response = $this->container->call($this->routeHandler, $this->params);
                return $this->normalizeResponse($request, $response);
            }
        };
    }
}
