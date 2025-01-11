# Simple route for PHP
simple, fast, modern, flexible PHP routing. PSR-7, PSR-15 compatible. 

## Installation
This package requires PHP version 8.2 or later.
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

anywhere:
```php
use Az\Route\Router;
...
$router = new Router(['../config/routes.php', '../module/routes.php']);

if (!$route = $this->router->match($request)) {
    //abort(404)
}

$handler = $route->getInstance($container);
$response = $handler->handle($request);
```

#### When using a dependency container and pipeline
In the dependency container:
```php
...
use Az\Route\RouterInterface;
...

return [
    ...
    RouterInterface::class => fn() => new Router('../config/routes.php'),
    ...
]
```

Add middleware to end of pipeline:
```php
...
$this->pipe(RouteBootstrap::class);
$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
```
## Features
- Very simple record for route: 
  ```php
  'name' => [pattern, handler, tokens],   
  'auth' => ['/auth/{action?}', Auth::class]
  ```
- Fine-tuning of the route is done through attributes:
  ```php
  ...
  use Az\Route\Route;
  ...
  #[Route(methods: 'any')]
  #[Route(host: 'localhost')]
  class ClassName
  {
    #[Route(filter: 'some_function')]
    #[Route(ajax: false, tokens: ['id' => '\d+'])]
    public function show($id)

    #[Route(methods: ['post', 'patch'])]
    public function save($id = null)
  }
  ```
