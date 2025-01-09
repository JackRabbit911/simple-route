<?php declare(strict_types=1);

use Az\Route\RouterInterface;
use HttpSoft\Response\HtmlResponse;
use Twig\Environment;

function render($file, $data)
{
    extract($data, EXTR_SKIP);               
    ob_start();
    include $file;
    return ob_get_clean();
}

function container()
{
    global $container;
    return $container;
}

function view(string $file, array $data = [], $reponse_interface = true)
{
    $tpl = container()->get(Environment::class);
    $str = $tpl->render($file, $data);
    return $reponse_interface ? new HtmlResponse($str) : $str;
}

function path(string $routeName, array $params = [])
{
    $router = container()->get(RouterInterface::class);
    return $router->path($routeName, $params);
}
