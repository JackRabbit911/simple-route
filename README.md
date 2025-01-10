# Simple route for PHP
simple, fast, modern, flexible PHP routing. PSR-7, PSR-15 compatible. 

## Installation
This package requires PHP version 7.4 or later.
```
composer require alpha-zeta/simple-route
```

## Usage
anywere, ../config/routes.php, for example:
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

in PostPontroller.php:
```php
...
use Az\Route\Route;
...
#[Route(methods: 'post')]
public function save()
{
}
```

anywere, for example, in some middleware:
```
...
use Az\Route\Router;
...

public function __construct(RouterInterface $router)
{
    $this->router = $router;
}

public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
{
    $this->router->routes(require '../config/routes.php');

    return handler($request);
}
```
