<?php

use Az\Route\Matcher;
use Az\Route\RouteFactory;
use Az\Route\Router;
use Az\Route\RouterInterface;
use HttpSoft\Emitter\EmitterInterface;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\Runner\MiddlewarePipeline;
use HttpSoft\Runner\MiddlewarePipelineInterface;
use HttpSoft\Runner\MiddlewareResolver;
use HttpSoft\Runner\MiddlewareResolverInterface;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    ServerRequestInterface::class => fn() => (new ServerRequestCreator())->create(),
    MiddlewarePipelineInterface::class => fn() => new MiddlewarePipeline(),
    MiddlewareResolverInterface::class => fn(ContainerInterface $c) => new MiddlewareResolver($c),
    EmitterInterface::class => fn() => new SapiEmitter(),
    RouterInterface::class => fn() => new Router(),
    Environment::class => function () {
        $loader = new FilesystemLoader('../app/views');
        return new Environment($loader);
    },
];
