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
use Az\Route\Route;
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
$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
```
## Features
* Very simple record for route: 
  ```php
  'name' => [pattern, handler, tokens],   
  'auth' => ['/auth/{action?}', Auth::class]
  ```
  + Each route is named
  + The route can be applied to a function, anonymous function, method, or the entire controller
  + {action} is reserved token name, means the name of a controller method
  + Default controller method name is __invoke
  + Default http request methods is ['HEAD', 'GET']
  + The delimeters me be any valid character

* Fine-tuning of the route is done through attributes:
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
  + tokens - redefines default token: `'\w*'`
  + methods - redefines request methods
  + host - filter by $request->getHost() (default - no filter)
  + ajax - filter by 'x_requested_with' header (default - no filter)
  + filter - any named function or method, takes the route as the first parameter, and the request as the second  
    ```php
    function my_filter(Az\Route\Route $route, ServerRequestInterface $request): bool
    ```
* Request handler (controller) record may be:
  ```php
  - fn($arg) => new Request(...)
  - function ($arg1, $arg2) {...}
  - ClassName::class
  - [ClassName::class, 'methodName']
  - 'Ns\ClassName::methodName' or 'Ns\ClassName@methodName'
  - $instance or [$instance, 'methodName']
  ```
  Before invoke the controller is wrapped in class implements RequestHandleInterface. 
  Therefore, we recommend using a controller that implements the RequestHandlerInterface right away
* Router does not create instances of the Route class until match pattern and uri. This saves memory and time.

### Happy use for You!
**Try the [demo](https://github.com/JackRabbit911/simple-route-demo) for get more examples**
