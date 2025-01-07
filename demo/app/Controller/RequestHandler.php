<?php declare(strict_types=1);

namespace App\Controller;

use Az\Route\Route;
use HttpSoft\Response\HtmlResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use DI\Attribute\Inject;

abstract class RequestHandler implements RequestHandlerInterface
{
    #[Inject]
    protected ContainerInterface $container;
    protected Route $route;
    protected ServerRequestInterface $request;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->route = $request->getAttribute(Route::class);
        $this->request = $request;
        [$class, $method] = $this->route->getHandler();
        $params = $this->route->getParameters();

        if (method_exists($this->container, 'call')) {
            return $this->container->call([$this, $method], $params);
        }

        return call_user_func_array([$this, $method], array_values($params));
    }
}
