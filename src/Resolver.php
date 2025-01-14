<?php declare(strict_types=1);

namespace Az\Route;

use Az\Route\Route;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Closure;

class Resolver
{
    private ?ContainerInterface $container;

    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($handler)
    {
        if (is_array($handler)) {
            return $this->array($handler);
        }

        if ($handler instanceof RequestHandlerInterface) {
            return $handler;
        }

        if ($handler instanceof Closure || function_exists($handler)) {
            return $this->func($handler);
        }
    }

    private function func($handler)
    {
        return new class ($handler, $this->container) implements RequestHandlerInterface
        {
            private $func;
            private ContainerInterface $container;

            public function __construct(Closure|string $handler, ContainerInterface $container)
            {
                $this->func = $handler;
                $this->container = $container;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $route = $request->getAttribute(Route::class);
                $params = array_merge(['request' => $request], $route->getParameters());

                return (method_exists($this->container, 'call')) 
                    ? $this->container->call($this->func, $params)
                    : call_user_func_array($this->func, array_values($params));
            }
        };
    }

    private function array(array $handler)
    {
        $instance = $this->getInstance($handler[0]);

        if ($instance instanceof RequestHandlerInterface) {
            return $instance;
        }

        return new class ($instance, $this->container) implements RequestHandlerInterface
        {
            private $instance;
            private $container;

            public function __construct($instance, ?ContainerInterface $container)
            {
                $this->instance = $instance;
                $this->container = $container;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $route = $request->getAttribute(Route::class);
                $action = $route->getHandler()[1];
                $params = $route->getParameters();

                return ($this->container && method_exists($this->container, 'call')) 
                    ? $this->container->call([$this->instance, $action], $params)
                    : call_user_func_array([$this->instance, $action], array_values($params));
            }
        };
    }

    private function getInstance($handler)
    {
        if (is_object($handler) || function_exists($handler)) {
            return $handler;
        }

        if (!$this->container) {
            return new $handler;
        }

        return $this->container->get($handler);
    }
}
