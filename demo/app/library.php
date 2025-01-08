<?php declare(strict_types=1);

use HttpSoft\Response\HtmlResponse;

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

function view(string $file, array $data = [])
{
    $tpl = container()->get('tpl');
    $str = $tpl->render($file, $data);
    return new HtmlResponse($str);
}
