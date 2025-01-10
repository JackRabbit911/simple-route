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
    'post.save' => ['/post/{id?}', [PostController::class, 'save']]
]
```
