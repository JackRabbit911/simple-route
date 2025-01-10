<?php

use App\Controller\SimpleController;
use App\Controller\SimpleHandler;
use App\FileResponse;
use Az\Route\Route;

function isFile($route)
{
    $file = '../' . $route->getParameters()['file'];
    return is_file($file);
}

return [
    'home'      => ['/', SimpleHandler::class],
    'articles'  => ['/articles', [SimpleHandler::class, 'list']],
    'article'   => ['/article/{id}/{slug?}', [SimpleHandler::class, 'show'], ['slug' => '[\w-]*']],
    'save'      => ['/article/save', [SimpleHandler::class, 'save']],
    'about'     => ['/about/{action?}', SimpleController::class],
    'file'      => ['/file/{file}', 
                    #[Route(filter: 'isFile')] fn($file) => new FileResponse($file), 
                    ['file' => '.*']],
    'about'     => ['/about/{action?}', SimpleController::class],
    'about.post'=> ['/about/project', [SimpleController::class, 'save']], //method POST
];
