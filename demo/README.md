# Demo application for alpha-zeta/simple-route
This example demonstrates how to write a simple application using simple-route without any framework.

## Disclaimer
Watch out, this project is meant to show how to how to embed simple-route library into application and use it. Be aware that this code is only meant for learning purposes and should probably not go to production as-is.

## Run
To run this demo, you need install simle-route lidrary or clone this repository:
```
composer require alpha-zeta/simple-route
or
git clone https://github.com/JackRabbit911/simple-route
```
You can then run the web application using PHP's built-in server:
```
php -S 0.0.0.0:8000 -t demo/public/ demo/public/router.php
```
The web application is running at http://localhost:8000.

> [!NOTE]
> .htaccess does not work correctly with PHP's built-in server, so we use the `demo/public/router.php` to emulate ModRewrite. This file has no relation to our library.

## How it works
1. `index.php` - execute `$app->run()` method.
2. `$app->run()` - register midlewares from `config/pipeline.php` to pipeline and run `pipeline->process(...)`
3. We are interested in two files from the `config/pipeline.php`: `RouteMatch.php` and `RouteDispatch.php`
4. `RouteMatch` class creates instance of `Router` class, through a dependency container. File `app/config/container.php`, string:  
   `ReouterInterface::class => fn() => new Router('..\app\config\routes.php')`
5. Instance of `Router` checks if a `$request->getUri()->getPath()` matches a pattern and applies additional checks and filters. In success, returns `Route` instance.
6. `RouteDispatch` middleware creates instance of the controller, wrapped in class implements RequestHandleInterface end executes method `handle($request)` that returns a response implements `RequestHandlerInterface`.
7. Finally, the response is fed to the emitter
