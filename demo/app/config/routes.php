<?php

use App\Controller\SimpleController;
use App\Controller\SimpleHandler;
use HttpSoft\Response\HtmlResponse;

return [
    'home'      => ['/', fn() => new HtmlResponse("Hello, world!")],
    'articles'  => ['/articles', SimpleHandler::class],
    'article'   => ['/article/{id}', [SimpleHandler::class, 'show'], ['id' => '\d+']],
    'about'     => ['/about/{action?}', SimpleController::class],
];
