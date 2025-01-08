<?php declare(strict_types=1);

function render($file, $data)
{
    extract($data, EXTR_SKIP);               
    ob_start();
    include $file;
    return ob_get_clean();
}
