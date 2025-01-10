# Simple route for PHP
simple, fast, modern, flexible PHP routing. PSR-7, PSR-15 compatible. 

## Installation
This package requires PHP version 7.4 or later.
```
composer require alpha-zeta/simple-route
```

## Usage
anywhere, ../config/routes.php, for example:
```php
return [
    'home'      => ['/', fn() => new HtmlResponse('This is homepage')],
    'about'     => ['/about', AboutHandler::class],
    'auth'      => ['/auth/{action?}' Auth::class],
    'posts'     => ['/posts', [PostController::class, 'list']],
    'post'      => ['/post/{id}/{slug?}', PostController::class, ['slug' => '[\w-]*']],
    'post.save' => ['/post/{id?}', [PostController::class, 'save']],
]
```

in PostController.php:
```php
...
use Az\Route\Route;
...
#[Route(methods: 'post')]
public function save()
{
}
```

anywhere, for example, in middleware RouteBootstrap.php:
```php
...
use Az\Route\Router;
...

class RouteBootstrap implements MiddlewareInterface

public function __construct(RouterInterface $router)
{
    $this->router = $router;
}

public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
{
    $this->router->routes(require '../config/routes.php');
    $this->router->routes(require '../module/config/routes.php');
    return handler($request);
}
```

Add middleware to end of pipeline:
```php
...
$this->pipe(RouteBootstrap::class);
$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
```
