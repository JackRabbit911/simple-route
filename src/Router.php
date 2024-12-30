<?php declare(strict_types=1);

namespace Az\Route;

use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    private Matcher $matcher;
    private RouteFactory $factory;
    private array $routes = [];
    private ?array $reflect = null;
    public ?string $allowedMethods = null;

    public function __construct(Matcher $matcher, RouteFactory $factory)
    {
        $this->matcher = $matcher;
        $this->factory = $factory;
    }

    public function routes(array $routes): void
    {
        $this->routes = array_merge($this->routes, $routes);
    }

    public function path(string $name, array $params): string
    {
        $pattern = $this->routes[$name][0];
        return $this->matcher->path($name, $pattern, $params);
    }

    public function getReflect()
    {
        return $this->reflect;
    }

    public function match(ServerRequestInterface $request): mixed
    {
        $path = $request->getUri()->getPath();

        foreach ($this->routes as $name => $item) {
            [$pattern, $handler] = $item;
            $methods = $item[2] ?? ['get'];

            $params = $this->matcher->match($pattern, $path);

            if ($params !== false) {
                $handler = $this->factory->handler($handler, $params);
                $this->reflect = $this->factory->reflect($handler);

                if (!$this->reflect) {
                    return false;
                }

                $route = $this->factory->create($handler, $methods);
                $route->setParameters($params);

                return $this->check($route, $request);
            }
        }

        return false;
    }

    private function check($route, $request)
    {
        $methods = $route->getMethods();
        $host = $route->getHost();
        $filters = $route->getFilters();
        $tokens = $route->getTokens();

        if (!empty($tokens)) {
            $parameters = $route->getParameters();

            foreach ($tokens as $key => $pattern) {
                if (!preg_match('~^(' . $pattern . ')$~i', $parameters[$key] ?? '')) {
                    return false;
                }
            }
        }

        if (!in_array($request->getMethod(), $methods, true)) {
            $this->allowedMethods = implode(', ', array_unique(array_filter($methods)));
            return false;
        }

        if ($host && !preg_match('~^' 
                . str_replace('.', '\\.', $host) 
                . '$~i', $request->getUri()->getHost())) {
            return false;
        }

        if ($route->isAjax() !== null && $route->isAjax() !== $this->is_ajax($request)) {
            return false;
        }

        foreach ($filters as $filter) {
            if (!$filter($route, $request)) {
                return false;
            }
        }

        return $route;
    }

    private function is_ajax(ServerRequestInterface $request)
    {
        $key = 'x_requested_with';
        $header = $request->getHeaderLine($key);

        if (empty($header)) {
            $header = $request->getHeaderLine('http_' . $key);
        }

        if (empty($header)) {
            $header = $request->getHeaderLine(strtoupper($key));
        }

        if (empty($header)) {
            $header = $request->getHeaderLine(strtoupper('http_' . $key));
        }

        if (empty($header)) {
            return false;
        }

        return true;
    }
}