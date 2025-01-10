<?php declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\TwigFunction;

class TemplateSettings implements MiddlewareInterface
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {        
        $path = $request->getUri()->getPath();
        $this->twig->addGlobal('uri', $path);

        $this->twig->addFunction(new TwigFunction(
            'active', function ($href) use ($path) {
                return $href === $path;
            }));

        $this->twig->addFunction(new TwigFunction(
            'path', function ($routeName, $params = []) {
                return path($routeName, $params);
            }));
            

        return $handler->handle($request);
    }
}
